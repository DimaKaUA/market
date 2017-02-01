<?php

include_once ROOT . '/models/Item.php';
include_once ROOT . '/models/Category.php';
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
     * Adds an item to the cart using the asynchronous request (ajax)
     * @param integer $id <p>item id</p>
     */
    public function addAjaxAction($id)
    {
        // Add the item to cart and print the result: the number of items in cart
        Cart::addItem($id);
        return true;
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
                'categories' => $categories,
                'items'      => $items,
                'totalPrice' => $totalPrice,
        ]);

        return true;
    }
    /**
     * Checkouts
     */
    public function checkoutAction()
    {   
        // Gets data from cart  
        $itemsInCart = Cart::getItems();
        // If no items, send users to search for items in the home
        if ($itemsInCart == false) {
            header("Location: /");
            exit;
        }
        // Categories list for the left menu
        $categories = Category::getCategoriesList();
        // Find the total cost
        $itemsIds = array_keys($itemsInCart);
        $items = Item::getItemsByIds($itemsIds);
        $totalPrice = Cart::getTotalPrice($items);
        // Number of items
        $totalQuantity = Cart::countItems();
        // The fields for the form
        $userName = false;
        $userPhone = false;
        $userComment = false;
        // Status successful checkout
        $result = false;
        // Check whether the guest user
        if (!User::isGuest()) {
            // If the user is not a guest
            // get information about the user from the database
            $userId = User::checkLogged();
            $user = User::getUserById($userId);
            $userName = $user['name'];
        } else {
            // If the guest, the form fields remain empty
            $userId = false;
        }
        // Processing forms
        if (isset($_POST['submit'])) {
            // If the form is submitted
            // get the data from the form
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            // error flag
            $errors = false;
            // Validation of fields
            if (!User::checkName($userName)) {
                $errors[] = 'Wrong name';
            }
            if (!User::checkPhone($userPhone)) {
                $errors[] = 'Wrong phone';
            }
            if ($errors == false) {
                // If there are no errors
                // Save the order in database
                $result = Order::save($userName, $userPhone, $userComment, $userId, $itemsInCart);
                if ($result) {
                    // If the order is successfully saved
                    // Notify the administrator about the new order by mail              
                    $adminEmail = 'php.start@mail.ru';
                    $message = '<a href="http://digital-mafia.net/admin/orders">Список заказов</a>';
                    $subject = 'Новый заказ!';
                    mail($adminEmail, $subject, $message);
                    // Clear the cart
                    Cart::clear();
                }
            }
        }

        $this->view->generate('cart/index_view.twig.html', [
                'categories' => $categories,
                'items'      => $items,
                'totalPrice' => $totalPrice,
        ]);
        return true;
    }
}