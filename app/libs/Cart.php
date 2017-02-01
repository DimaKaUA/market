<?php
/**
 * Class Cart
 * The component for the cart
 */
class Cart
{
    /**
     * Adds an item to the cart (session)
     * @param integer $id <p>item id</p>
     * @return integer <p>Count of the items in the cart</p>
     */
    public static function addItem($id)
    {
        // $id to integer type
        $id = intval($id);
        // An empty array of items in cart
        $itemsInCart = array();
        // If the cart is not empty (they are stored in the session)
        // That will fill the array of items
        if (isset($_SESSION['items'])) {
            $itemsInCart = $_SESSION['items'];
        }
        // Check whether there is already such an item in the shopping cart
        if (array_key_exists($id, $itemsInCart)) {
            // If such items are in the basket, but was added once again, increase the number by 1
            $itemsInCart[$id] ++;
        } else {
            // If not, add the id of the new items in the cart with the number 1
            $itemsInCart[$id] = 1;
        }
        // Store the array of items in the session
        $_SESSION['items'] = $itemsInCart;
        // Return the number of items in cart
        return self::countItems();
    }
    /**
     * Counts the number of items in the basket (in the session)
     * @return integer <p>Number of items in cart</p>
     */
    public static function countItems()
    {
        // Checking items in cart
        if (isset($_SESSION['items'])) {
            // If there is the array of items
            // calculate the amount of it and return 
            $count = 0;
            foreach ($_SESSION['items'] as $id => $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        } else {
            // If no items, return 0
            return 0;
        }
    }
    /**
     * Returns an array of IDs and the number of items in cart<br/>
     * Если товаров нет, возвращает false;
     * @return mixed: boolean or array
     */
    public static function getItems()
    {
        if (isset($_SESSION['items'])) {
            return $_SESSION['items'];
        }
        return false;
    }
    /**
     * Gets the total cost of items
     * @param array $items <p>An array of information about items</p>
     * @return integer <p>Total cost</p>
     */
    public static function getTotalPrice(array $items)
    {
        // Get an array of IDs and the number of items in cart
        $itemsInCart = self::getitems();
        // Count the total cost
        $total = 0;
        if ($itemsInCart) {
            // If the basket is not empty
            // pass by passed into the method array of items
            foreach ($items as $item) {
                // Find the total cost: price * quantity
                $total += $item['price'] * $itemsInCart[$item['id']];
            }
        }
        return $total;
    }
    /**
     * Cleans the basket
     */
    public static function clear()
    {
        if (isset($_SESSION['items'])) {
            unset($_SESSION['items']);
        }
    }
    /**
     * Removes the item with the given id from the basket
     * @param integer $id <p>item id</p>
     */
    public static function deleteItem($id)
    {
        // Gets an array of IDs and the number of items in cart
        $itemsInCart = self::getitems();
        // Remove from the array element with the specified id
        unset($itemsInCart[$id]);
        // Write an array of items from remote element in the session
        $_SESSION['items'] = $itemsInCart;
    }
}