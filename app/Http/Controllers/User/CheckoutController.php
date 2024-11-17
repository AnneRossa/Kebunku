<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = 'SB-Mid-server-GaCA-xPH36Uhart3mRs1T0fW';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Validate the incoming address data
        $validated = $request->validate([
            'address.name' => 'required|string|max:255',
            'address.address1' => 'required|string|max:255',
            'address.kelurahan' => 'required|string|max:255',
            'address.kecamatan' => 'required|string|max:255',
            'address.kota' => 'required|string|max:255',
            'address.provinsi' => 'required|string|max:255',
            'address.nomorTlp' => 'required|string|max:15',
            'address.type' => 'required|string|max:255',
        ]);

        $newAddress = $request->address;

        // Handle saving the new address if it's provided
        if (!empty($newAddress['address1'])) {
            // Ensure only one address is marked as main
            UserAddress::where('isMain', 1)->update(['isMain' => 0]);

            // Save the new address
            $address = new UserAddress();
            $address->name = $newAddress['name'];
            $address->address1 = $newAddress['address1'];
            $address->kelurahan = $newAddress['kelurahan'];
            $address->kecamatan = $newAddress['kecamatan'];
            $address->kota = $newAddress['kota'];
            $address->provinsi = $newAddress['provinsi'];
            $address->type = $newAddress['type'];
            $address->nomorTlp = $newAddress['nomorTlp'];

            // If an image is uploaded, handle the image data
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageContent = file_get_contents($image->getRealPath());
                $address->image = $imageContent;
            }

            $address->user_id = $user->id;
            $address->isMain = 1; // Mark this address as main
            $address->save();
    }

    // Get the main address
    $mainAddress = $user->user_address()->where('isMain', 1)->first();
    if (!$mainAddress) {
        return response()->json(['status' => 'error', 'message' => 'Main address not found.'], 400);
    }

    // Create the order
    $order = new Order();
    $order->order_number = 'KBKU-' . mt_rand(10000, 99999);
    $order->status = 'pending'; // Set status to pending until payment is confirmed
    $order->total_price = 0; // Initial total price
    $order->created_by = $user->id;
    $order->user_address_id = $mainAddress->id;
    $order->session_id = 'N/A';
    $order->save();

    $orderId = $order->id;

    // Fetch and process cart items
    $cartItems = CartItem::where('user_id', $user->id)->get();
    $order_items = [];
    $totalPrice = 0;

    foreach ($cartItems as $cartItem) {
        $product = $cartItem->product;
        $quantity = $cartItem->quantity;
        $unitPrice = $product->price;

        // Create order item
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'price' => $unitPrice * $quantity // Calculated price
        ]);

        // Append to order items array
        $order_items[] = [
            'id' => $item->product_id,
            'price' => $item->unit_price,
            'quantity' => $item->quantity,
            'name' => $item->product->title,
        ];

        // Add to total price
        $totalPrice += $item->price;

        // Remove cart item
        $cartItem->delete();
    }

    // Update the total price
    $order->total_price = $totalPrice;
    $order->save();

    // Clear cart cookies
    Cart::setCookieCartItems([]);

    // Prepare the payload for Midtrans
    $payload = [
        'transaction_details' => [
            'order_id' => $order->order_number,
            'gross_amount' => $order->total_price
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email
        ],
        'item_details' => $order_items
    ];

    try {
        // Generate Snap token
        $snapToken = Snap::getSnapToken($payload);

        // Create payment record
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'status' => 'pending',
            'type' => 'midtrans',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        Payment::create($paymentData);

        // Return Snap token
        return response()->json([
            'status' => 'success',
            'snap_token' => $snapToken,
            'redirect_url' => route('dashboard')
        ]);

    } catch (\Exception $e) {
        Log::error('Midtrans error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Payment processing failed.'], 500);
    }
}




}
