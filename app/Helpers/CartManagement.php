<?php
namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement {
    // add item to cart
    static public function addItemToCart($product_id){
        $cart_items = self::getCartItemsFromCookie();
        $existing_item = null;
        foreach($cart_items as $key => $items){
            if($items['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }
        if($existing_item !== null){
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * 
            $cart_items[$existing_item]['unit_amount'];
        }
        else{
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'image' =>$product->images[0],
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }
    // add item with quantity
    static public function addItemToCartWithQty($product_id, $quantity = 1){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;
        foreach($cart_items as $key => $items){
            if($items['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }
        if($existing_item !== null){
            $cart_items[$existing_item]['quantity'];
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * 
            $cart_items[$existing_item]['unit_amount'];
        }
        else{
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'image' =>$product->images[0],
                    'quantity' => $quantity,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }
    // remove item
    static public function removeCartItem($product_id){
        // Gọi hàm tĩnh getCartItemsFromCookie để lấy danh sách các mục trong giỏ hàng từ cookie và lưu vào biến $cart_items.
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $keys=> $item){
            if($item['product_id'] == $product_id){
                unset($cart_items[$keys]);
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }
    // add cart items to cookies
    
    static public function addCartItemsToCookie($cart_items){
        //  lưu trữ dữ liệu giỏ hàng dưới dạng JSON, và thời gian tồn tại của cookie này là 30 ngày
        // . Cookie này sẽ được gửi kèm với phản hồi HTTP tiếp theo từ server đến client.
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }
    // clear cart items from cookie
    static public function clearCartItems(){
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // get all cart items from cookie
    static public function getCartItemsFromCookie(){
        /* Cookie cart_items được lấy ra bằng phương thức Cookie::get('cart_items'), 
        sau đó dùng json_decode() để chuyển dữ liệu từ định dạng JSON sang mảng PHP.
        Tham số true của json_decode chỉ định rằng đối tượng JSON sẽ được chuyển thành mảng thay vì đối tượng PHP. 
        */
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if(!$cart_items){
            $cart_items = [];
        }
        return $cart_items;
    }

    // increment item quanity
    static public function incrementQuantityTCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $items){
            if($items['product_id'] == $product_id){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }
    // decrement item quantity
    static public function decrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $items){
            if($items['product_id'] == $product_id){
                if($cart_items[$key]['quantity'] > 1){
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                }
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }
    // calculate grand total
    static public function calculateGrandTotal($items){
        return array_sum(array_column($items, 'total_amount'));
    }
}