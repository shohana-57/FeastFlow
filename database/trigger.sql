CREATE OR REPLACE TRIGGER trg_update_stock
AFTER INSERT ON order_items
FOR EACH ROW
DECLARE
    CURSOR c_ingredients IS
        SELECT inventory_id, quantity_needed
        FROM menu_item_ingredients
        WHERE menu_item_id = :NEW.menu_item_id;
BEGIN
    FOR ing IN c_ingredients LOOP
        UPDATE inventory
        SET quantity = quantity - (ing.quantity_needed * :NEW.quantity),
            updated_at = SYSDATE
        WHERE id = ing.inventory_id;
    END LOOP;
END;
/


CREATE OR REPLACE TRIGGER trg_free_table
AFTER UPDATE OF status ON orders
FOR EACH ROW
WHEN (NEW.status = 'paid')
BEGIN
    UPDATE restaurant_tables
    SET status = 'free'
    WHERE id = :NEW.table_id;
END;
/

CREATE OR REPLACE TRIGGER trg_order_audit
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    INSERT INTO audit_log(id, table_name, action, record_id)
    VALUES(audit_log_seq.NEXTVAL, 'ORDERS', 'STATUS_UPDATE', :NEW.id);
END;
/