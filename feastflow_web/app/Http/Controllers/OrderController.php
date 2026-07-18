<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected function findMenuItem($id)
    {
        $item = DB::select(
            "SELECT m.*, c.name as category_name
             FROM menu_items m
             JOIN categories c ON m.category_id = c.id
             WHERE m.id = ?",
            [$id]
        );

        if (count($item) > 0) {
            return $item[0];
        }

        $item = DB::select(
            "SELECT * FROM available_menu WHERE id = ?",
            [$id]
        );

        return count($item) > 0 ? $item[0] : null;
    }

    protected function getOrder($orderId, $customerId)
    {
        $order = DB::select(
            "SELECT o.id, o.table_id, o.customer_id, o.status,
                    oi.quantity, oi.unit_price,
                    m.id AS menu_item_id, m.name AS item_name,
                    (oi.quantity * oi.unit_price) AS item_subtotal
             FROM orders o
             JOIN order_items oi ON o.id = oi.order_id
             JOIN menu_items m ON oi.menu_item_id = m.id
             WHERE o.id = ? AND o.customer_id = ?",
            [$orderId, $customerId]
        );

        return count($order) > 0 ? $order[0] : null;
    }

    protected function getAvailableTables()
    {
        return DB::select(
            "SELECT * FROM restaurant_tables WHERE status = 'free' ORDER BY table_number"
        );
    }

    protected function getOrderList()
    {
        $userId = session('user_id');
        $role = session('user_role');

        $baseQuery = "SELECT o.id, o.status, o.table_id, o.customer_id, u.name AS customer_name,
                             NVL(SUM(oi.quantity * oi.unit_price), 0) AS total,
                             o.created_at
                      FROM orders o
                      JOIN users u ON o.customer_id = u.id
                      LEFT JOIN order_items oi ON oi.order_id = o.id";

        if ($role === 'waiter' || $role === 'admin' || $role === 'manager') {
            $baseQuery .= " GROUP BY o.id, o.status, o.table_id, o.customer_id, u.name, o.created_at
                             ORDER BY o.created_at DESC";
            return DB::select($baseQuery);
        }

        $baseQuery .= " WHERE o.customer_id = ?
                         GROUP BY o.id, o.status, o.table_id, o.customer_id, u.name, o.created_at
                         ORDER BY o.created_at DESC";

        return DB::select($baseQuery, [$userId]);
    }

    public function showBookingForm(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to book a table.');
        }

        $tables = $this->getAvailableTables();

        return view('booking.create', ['tables' => $tables]);
    }

    public function storeBooking(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to book a table.');
        }

        $request->validate([
            'table_id' => 'required|integer',
        ]);

        $tableId = $request->input('table_id');
        $customerId = session('user_id');

        $table = DB::select(
            "SELECT * FROM restaurant_tables WHERE id = ? AND status = 'free'",
            [$tableId]
        );

        if (count($table) === 0) {
            return back()->with('error', 'Selected table is not available.');
        }

        DB::statement(
            "INSERT INTO bookings (id, table_id, customer_id, status, booked_at)
             VALUES (bookings_seq.NEXTVAL, ?, ?, 'reserved', SYSDATE)",
            [$tableId, $customerId]
        );
        DB::statement(
            "UPDATE restaurant_tables SET status = 'reserved' WHERE id = ?",
            [$tableId]
        );

        return redirect('/booking/create')->with('success', 'Table booked successfully. We will reserve it for you.');
    }

    public function index(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to view orders.');
        }

        $orders = $this->getOrderList();
        return view('orders.index', ['orders' => $orders]);
    }

    public function show($id)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to view order details.');
        }

        $userId = session('user_id');
        $role = session('user_role');

        $order = DB::select(
            "SELECT o.id, o.table_id, o.customer_id, o.status, o.created_at,
                    u.name AS customer_name, u.role AS customer_role
             FROM orders o
             JOIN users u ON u.id = o.customer_id
             WHERE o.id = ?",
            [$id]
        );

        if (count($order) === 0) {
            return redirect('/orders')->with('error', 'Order not found.');
        }

        $order = $order[0];

        if ($role !== 'waiter' && $role !== 'admin' && $role !== 'manager' && $order->customer_id !== $userId) {
            return redirect('/orders')->with('error', 'You do not have access to this order.');
        }

        $items = DB::select(
            "SELECT oi.quantity, oi.unit_price, m.name AS item_name,
                    (oi.quantity * oi.unit_price) AS item_subtotal
             FROM order_items oi
             JOIN menu_items m ON oi.menu_item_id = m.id
             WHERE oi.order_id = ?",
            [$id]
        );

        $payment = DB::select("SELECT * FROM payments WHERE order_id = ?", [$id]);
        $payment = count($payment) ? $payment[0] : null;

        return view('orders.show', [
            'order' => $order,
            'items' => $items,
            'payment' => $payment,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to update order status.');
        }

        $role = session('user_role');
        if ($role !== 'waiter' && $role !== 'admin' && $role !== 'manager') {
            return redirect('/orders')->with('error', 'Unauthorized to update orders.');
        }

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,paid',
        ]);

        $status = $request->input('status');

        DB::statement("UPDATE orders SET status = ? WHERE id = ?", [$status, $id]);

        return redirect('/orders/' . $id)->with('success', 'Order status updated to ' . $status . '.');
    }

    public function create(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to place an order.');
        }

        $itemId = $request->query('item_id');
        $item = $this->findMenuItem($itemId);

        if (!$item) {
            return redirect('/menu')->with('error', 'Menu item not found.');
        }

        $availableTables = $this->getAvailableTables();

        return view('order.create', ['item' => $item, 'tables' => $availableTables]);
    }

    public function store(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to place an order.');
        }

        $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'table_id' => 'required|integer',
        ]);

        $item = $this->findMenuItem($request->input('item_id'));
        if (!$item) {
            return redirect('/menu')->with('error', 'Menu item not found.');
        }

        $customerId = session('user_id');
        $tableId = intval($request->input('table_id'));

        $table = DB::select("SELECT * FROM restaurant_tables WHERE id = ? AND status = 'free'", [$tableId]);
        if (count($table) === 0) {
            return back()->with('error', 'Selected table is not available.');
        }

        DB::statement(
            "INSERT INTO orders (id, table_id, customer_id, status)
             VALUES (orders_seq.NEXTVAL, ?, ?, 'pending')",
            [$tableId, $customerId]
        );

        $orderIdRow = DB::select("SELECT orders_seq.CURRVAL AS id FROM dual");
        $orderId = $orderIdRow[0]->id;

        if ($tableId !== null) {
            DB::statement(
                "UPDATE restaurant_tables SET status = 'occupied' WHERE id = ?",
                [$tableId]
            );
        }

        DB::statement(
            "INSERT INTO order_items (id, order_id, menu_item_id, quantity, unit_price)
             VALUES (order_items_seq.NEXTVAL, ?, ?, ?, ?)",
            [$orderId, $item->id, $request->input('quantity'), $item->price]
        );

        session(['order_data' => [
            'id' => $orderId,
            'item_id' => $item->id,
            'item_name' => $item->name,
            'quantity' => $request->input('quantity'),
            'unit_price' => $item->price,
            'total' => floatval($item->price) * intval($request->input('quantity')),
        ]]);

        return redirect('/order/payment?order_id=' . urlencode($orderId));
    }

    public function payment(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to make a payment.');
        }

        $orderId = $request->query('order_id');
        $order = $this->getOrder($orderId, session('user_id'));

        if (!$order) {
            return redirect('/menu')->with('error', 'No order found to pay for.');
        }

        return view('order.payment', ['order' => $order]);
    }

    public function processPayment(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to complete payment.');
        }

        $request->validate([
            'order_id' => 'required|integer',
            'payment_method' => 'required|string',
        ]);

        $order = $this->getOrder($request->input('order_id'), session('user_id'));
        if (!$order) {
            return redirect('/menu')->with('error', 'No order found to complete payment.');
        }

        try {
            DB::statement("BEGIN generate_bill(?, ?, ?); END;", [$order->id, 0, $request->input('payment_method')]);
        } catch (\Exception $e) {
            $subtotalRow = DB::select(
                "SELECT NVL(SUM(quantity * unit_price), 0) AS subtotal FROM order_items WHERE order_id = ?",
                [$order->id]
            );
            $subtotal = $subtotalRow[0]->subtotal ?? 0;
            $vat = $subtotal * 0.15;
            $total = $subtotal + $vat;

            DB::statement(
                "INSERT INTO payments (id, order_id, subtotal, vat, discount, total, method)
                 VALUES (payments_seq.NEXTVAL, ?, ?, ?, 0, ?, ?)",
                [$order->id, $subtotal, $vat, $total, $request->input('payment_method')]
            );
            DB::statement("UPDATE orders SET status = 'paid' WHERE id = ?", [$order->id]);
        }

        session(['payment_data' => [
            'order_id' => $order->id,
            'payment_method' => $request->input('payment_method'),
            'paid_at' => now()->toDateTimeString(),
        ]]);

        return redirect('/order/feedback?order_id=' . urlencode($order->id));
    }

    public function feedback(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to leave feedback.');
        }

        $order = $this->getOrder($request->query('order_id'), session('user_id'));
        if (!$order) {
            return redirect('/menu')->with('error', 'No completed order found for feedback.');
        }

        $payment = DB::select(
            "SELECT * FROM payments WHERE order_id = ?",
            [$order->id]
        );

        if (count($payment) === 0) {
            return redirect('/menu')->with('error', 'Payment not found for this order.');
        }

        return view('order.feedback', [
            'order' => $order,
            'payment' => $payment[0],
        ]);
    }

    public function submitFeedback(Request $request)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to send feedback.');
        }

        $request->validate([
            'order_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        $order = $this->getOrder($request->input('order_id'), session('user_id'));
        if (!$order) {
            return redirect('/menu')->with('error', 'No completed order found for feedback.');
        }

        DB::statement(
            "INSERT INTO feedback (id, customer_id, menu_item_id, rating, remarks)
             VALUES (feedback_seq.NEXTVAL, ?, ?, ?, ?)",
            [session('user_id'), $order->menu_item_id, $request->input('rating'), $request->input('comments')]
        );

        session()->forget('order_data');
        session()->forget('payment_data');

        return redirect('/order/thankyou')->with('success', 'Thank you! Your order and feedback have been received.');
    }

    public function thankyou()
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        return view('order.thankyou');
    }
}
