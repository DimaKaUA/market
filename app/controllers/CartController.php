<?php

include_once ROOT . '/models/Item.php';
include_once ROOT . '/models/Category.php';
include_once ROOT . '/models/Order.php';
include_once ROOT . '/libs/Cart.php';

/**
 * Controller CartController
 * Cart
 */
class CartController extends Controller {

    /**
     * Adds item to cart by synchronous request<br/>
     * 
     * @param integer $id <p>item id</p>
     */
    public function addAction($id)
    {
        // Add the item to cart
        Cart::addItem($id);
        // Return the user to the page from which it came
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
        exit;
    }
    
    /**
     * Deletes an item from the basket by synchronous request
     * @param integer $id <p>item id</p>
     */
    public function deleteAction($id)
    {
        // Remove specified items from the cart
        Cart::deleteItem($id);
        // Returns the user to the cart
        header("Location: /cart");
        exit;
    }
    /**
     * Displays the cart
     */
    public function indexAction()
    {
        $items = null;
        $totalPrice = null;
        // Categories list for the left menu
        $categoryModel = new Category();
        $categories = $categoryModel->getList();
        // Get the ID and the number of items in cart
        $itemsInCart = Cart::getItems();
        if ($itemsInCart) {
            // If the cart is not empty, get full information about the items for the list
            // get an array of items only with IDs
            $itemsIds = array_keys($itemsInCart);
            // Get the array of full information about needed items
            $itemModel = new Item();
            $items = $itemModel->getListByIds($itemsIds);
            // Get the total value of items
            $totalPrice = Cart::getTotalPrice($items);
        }

        $this->view->generate('cart/index_view.twig.html', [
                'categories'  => $categories,
                'items'       => $items,
                'totalPrice'  => $totalPrice,
                'itemsInCart' => $itemsInCart,
        ]);

        return true;
    }
    /**
     * Checkouts
     */
    public function checkoutAction()
    {   
        // error flag
        $errors = false;

        // Gets data from cart  
        $itemsInCart = Cart::getItems();
        // If no items, send users to search for items in the home
        if ($itemsInCart == false) {
            header("Location: /");
            exit;
        }
        // Categories list for the left menu
        $categoryModel = new Category();
        $categories = $categoryModel->getList();
        // Find the total cost
        $itemsIds = array_keys($itemsInCart);
        $itemModel = new Item();
        $items = $itemModel->getListByIds($itemsIds);
        $totalPrice = Cart::getTotalPrice($items);
        // Number of items
        $totalQuantity = Cart::countItems();
        // The fields for the form
        $userName = false;
        $userEmail = false;
        $userPhone = false;
        // Status successful checkout
        $result = false;
        // Processing forms
        if (isset($_POST['submit'])) {
            // If the form is submitted
            // get the data from the form
            $userName = $_POST['userName'];
            $userEmail = $_POST['userEmail'];
            $userPhone = $_POST['userPhone'];
            
            // Validation of fields
            if (!self::checkName($userName)) {
                $errors[] = 'Wrong name';
            }
            if (!self::checkEmail($userEmail)) {
                $errors[] = 'Wrong email';
            }
            if (!self::checkPhone($userPhone)) {
                $errors[] = 'Wrong phone';
            }
            if ($errors == false) {
                // Preparing arguments for Order model
                $args = array(
                            'user_name'   => $userName,
                            'user_email'  => $userEmail,
                            'user_phone'  => $userPhone,
                            'status'      => 1,
                            'items'       => serialize($itemsInCart),
                            'total_price' => $totalPrice,
                             );
                // If there are no errors
                // Save the order in database
                $orderModel = new Order();
                $result = $orderModel->addRecord($args);
                if ($result) {
                    // If the order is successfully saved
                    // Notify the administrator about the new order by mail              
                    $adminEmail = 'email@gmail.com';
                    $message = '<a href="http://localhost/orders">Order list</a>';
                    $subject = 'New order!';
                    mail($adminEmail, $subject, $message);
                    // Clear the cart
                    Cart::clear();
                }
            }
        }

        $this->view->generate('cart/checkout_view.twig.html', [
                'categories'    => $categories,
                'items'         => $items,
                'totalPrice'    => $totalPrice,
                'result'        => $result,
                'totalQuantity' => $totalQuantity,
                'errors'        => $errors, 
                'userName'      => $userName,
                'userEmail'     => $userEmail,
                'userPhone'     => $userPhone,
        ]);
        return true;
    }

    /**
     * Checks name: more than 2 chars
     * @param string $name <p>Name</p>
     * @return boolean <p>Result of performing of method</p>
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }
    /**
     * Checks phone: more than 10 chars
     * @param string $phone <p>Phone</p>
     * @return boolean <p>Result of performing of method</p>
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }

    /**
     * Check email
     * @param string $email <p>E-mail</p>
     * @return boolean <p>Result of performing of method</p>
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
}