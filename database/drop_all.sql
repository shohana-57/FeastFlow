--trigger drop
drop trigger rg_update_stock;
drop trigger trg_free_table;
drop trigger trg_order_audit;

--procedure drop
drop procedure place_order;
drop procedure generate_bill;

--function drop
drop function calculate_total;
drop function get_order_subtotal;

-- dropping views
drop view daily_sales_summary;
drop view popular_items;
drop view available_menu;

-- dropping table
drop table audit_log;
drop table order_items;
drop table staff;
drop table payments;
drop table orders;
drop table restaurant_tables;
drop table menu_item_ingredients;
drop table inventory;
drop table feedback;
drop table users;
drop table menu_items;
drop table categories;

--dropping sequence
DROP SEQUENCE audit_log_seq;
DROP SEQUENCE feedback_seq;
DROP SEQUENCE menu_item_ingredients_seq;
DROP SEQUENCE inventory_seq;
DROP SEQUENCE payments_seq;
DROP SEQUENCE order_items_seq;
DROP SEQUENCE orders_seq;
DROP SEQUENCE staff_seq;
DROP SEQUENCE restaurant_tables_seq;
DROP SEQUENCE menu_items_seq;
DROP SEQUENCE categories_seq;
DROP SEQUENCE users_seq;