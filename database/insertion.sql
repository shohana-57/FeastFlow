--user insertion
INSERT INTO users (id, name, email, password, role)
VALUES (users_seq.NEXTVAL, 'Admin User', 'admin@feastflow.com', '1234', 'admin');

INSERT INTO users (id, name, email, password, role)
VALUES (users_seq.NEXTVAL, 'Manager Rahim', 'rahim@feastflow.com', '1234', 'manager');

INSERT INTO users (id, name, email, password, role)
VALUES (users_seq.NEXTVAL, 'Waiter Karim', 'karim@feastflow.com', '1234', 'waiter');

INSERT INTO users (id, name, email, password, role)
VALUES (users_seq.NEXTVAL, 'Customer Jamal', 'jamal@feastflow.com', '1234', 'customer');

INSERT INTO users (id, name, email, password, role)
VALUES (users_seq.NEXTVAL, 'Customer Sadia', 'sadia@feastflow.com', '1234', 'customer');

COMMIT;

--categories insertion
INSERT INTO categories (id, name, description)
VALUES (categories_seq.NEXTVAL, 'Rice & Biryani', 'All rice and biryani items');

INSERT INTO categories (id, name, description)
VALUES (categories_seq.NEXTVAL, 'Burger & Sandwich', 'Fast food items');

INSERT INTO categories (id, name, description)
VALUES (categories_seq.NEXTVAL, 'Drinks', 'Hot and cold beverages');

INSERT INTO categories (id, name, description)
VALUES (categories_seq.NEXTVAL, 'Desserts', 'Sweet items');

INSERT INTO categories (id, name, description)
VALUES (categories_seq.NEXTVAL, 'Soup & Salad', 'Healthy options');

COMMIT;

--menu items insertion
INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 1, 'Chicken Biryani', 180, 'Special chicken biryani', 'available');

INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 1, 'Beef Tehari', 200, 'Special beef tehari', 'available');

INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 2, 'Chicken Burger', 150, 'Crispy chicken burger', 'available');

INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 2, 'Beef Burger', 170, 'Juicy beef burger', 'available');

INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 3, 'Coca Cola', 40, 'Cold drink 250ml', 'available');

INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 3, 'Fresh Lemonade', 60, 'Fresh lemon juice', 'available');

INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 4, 'Chocolate Cake', 120, 'Rich chocolate cake', 'available');

INSERT INTO menu_items (id, category_id, name, price, description, status)
VALUES (menu_items_seq.NEXTVAL, 5, 'Chicken Soup', 90, 'Hot chicken soup', 'available');

COMMIT;

--restaurant table insertion
INSERT INTO restaurant_tables (id, table_number, capacity, status)
VALUES (restaurant_tables_seq.NEXTVAL, 1, 2, 'free');

INSERT INTO restaurant_tables (id, table_number, capacity, status)
VALUES (restaurant_tables_seq.NEXTVAL, 2, 4, 'free');

INSERT INTO restaurant_tables (id, table_number, capacity, status)
VALUES (restaurant_tables_seq.NEXTVAL, 3, 4, 'free');

INSERT INTO restaurant_tables (id, table_number, capacity, status)
VALUES (restaurant_tables_seq.NEXTVAL, 4, 6, 'free');

INSERT INTO restaurant_tables (id, table_number, capacity, status)
VALUES (restaurant_tables_seq.NEXTVAL, 5, 8, 'free');

COMMIT;

--inventory insertion
INSERT INTO inventory (id, ingredient_name, quantity, unit, min_stock)
VALUES (inventory_seq.NEXTVAL, 'Rice', 50, 'kg', 10);

INSERT INTO inventory (id, ingredient_name, quantity, unit, min_stock)
VALUES (inventory_seq.NEXTVAL, 'Chicken', 30, 'kg', 5);

INSERT INTO inventory (id, ingredient_name, quantity, unit, min_stock)
VALUES (inventory_seq.NEXTVAL, 'Beef', 20, 'kg', 5);

INSERT INTO inventory (id, ingredient_name, quantity, unit, min_stock)
VALUES (inventory_seq.NEXTVAL, 'Flour', 25, 'kg', 5);

INSERT INTO inventory (id, ingredient_name, quantity, unit, min_stock)
VALUES (inventory_seq.NEXTVAL, 'Sugar', 15, 'kg', 3);

COMMIT;

-- Orders Insert
INSERT INTO orders (id, table_id, customer_id, status)
VALUES (orders_seq.NEXTVAL, 1, 4, 'pending');

INSERT INTO orders (id, table_id, customer_id, status)
VALUES (orders_seq.NEXTVAL, 2, 5, 'preparing');

INSERT INTO orders (id, table_id, customer_id, status)
VALUES (orders_seq.NEXTVAL, 3, 4, 'paid');

COMMIT;

-- Order Items Insert
INSERT INTO order_items (id, order_id, menu_item_id, quantity, unit_price)
VALUES (order_items_seq.NEXTVAL, 1, 1, 2, 180);

INSERT INTO order_items (id, order_id, menu_item_id, quantity, unit_price)
VALUES (order_items_seq.NEXTVAL, 1, 5, 2, 40);

INSERT INTO order_items (id, order_id, menu_item_id, quantity, unit_price)
VALUES (order_items_seq.NEXTVAL, 2, 3, 1, 150);

INSERT INTO order_items (id, order_id, menu_item_id, quantity, unit_price)
VALUES (order_items_seq.NEXTVAL, 3, 2, 1, 200);

COMMIT;

-- Payments Insert
INSERT INTO payments (id, order_id, subtotal, vat, discount, total, method)
VALUES (payments_seq.NEXTVAL, 3, 200, 30, 0, 230, 'cash');

COMMIT;

-- Staff Insert
INSERT INTO staff (id, user_id, position, shift, salary)
VALUES (staff_seq.NEXTVAL, 3, 'Waiter', 'morning', 15000);

INSERT INTO staff (id, user_id, position, shift, salary)
VALUES (staff_seq.NEXTVAL, 2, 'Manager', 'evening', 25000);

COMMIT;

-- Feedback Insert
INSERT INTO feedback (id, customer_id, menu_item_id, rating, remarks)
VALUES (feedback_seq.NEXTVAL, 4, 1, 5, 'Very delicious biryani!');

INSERT INTO feedback (id, customer_id, menu_item_id, rating, remarks)
VALUES (feedback_seq.NEXTVAL, 5, 3, 4, 'Good burger');

COMMIT;

-- Menu Item Ingredients 

INSERT INTO menu_item_ingredients (id, menu_item_id, inventory_id, quantity_needed)
VALUES (menu_item_ingredients_seq.NEXTVAL, 1, 1, 0.3);

INSERT INTO menu_item_ingredients (id, menu_item_id, inventory_id, quantity_needed)
VALUES (menu_item_ingredients_seq.NEXTVAL, 1, 2, 0.2);

INSERT INTO menu_item_ingredients (id, menu_item_id, inventory_id, quantity_needed)
VALUES (menu_item_ingredients_seq.NEXTVAL, 2, 1, 0.3);

INSERT INTO menu_item_ingredients (id, menu_item_id, inventory_id, quantity_needed)
VALUES (menu_item_ingredients_seq.NEXTVAL, 2, 3, 0.25);

INSERT INTO menu_item_ingredients (id, menu_item_id, inventory_id, quantity_needed)
VALUES (menu_item_ingredients_seq.NEXTVAL, 3, 4, 0.1);

INSERT INTO menu_item_ingredients (id, menu_item_id, inventory_id, quantity_needed)
VALUES (menu_item_ingredients_seq.NEXTVAL, 3, 2, 0.15);

COMMIT;
