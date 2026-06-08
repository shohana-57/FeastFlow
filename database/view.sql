--daily selling report

CREATE OR REPLACE VIEW daily_sales_summary AS
SELECT 
    TRUNC(p.paid_at) AS sale_date,
    COUNT(o.id) AS total_orders,
    SUM(p.total) AS revenue
FROM orders o 
JOIN payments p ON o.id = p.order_id
GROUP BY TRUNC(p.paid_at);

--popular items

CREATE OR REPLACE VIEW popular_items AS
SELECT 
    m.name,
    COUNT(oi.id) AS order_count,
    SUM(oi.quantity) AS total_quantity
FROM menu_items m 
JOIN order_items oi ON m.id = oi.menu_item_id
GROUP BY m.name;

--available items

CREATE OR REPLACE VIEW available_menu AS
SELECT 
    m.name,
    m.price,
    c.name AS category
FROM menu_items m
JOIN categories c ON m.category_id = c.id
WHERE m.status = 'available';

