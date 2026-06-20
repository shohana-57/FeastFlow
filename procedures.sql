-- to place order

CREATE OR REPLACE PROCEDURE place_order(
    p_table_id    IN NUMBER,
    p_customer_id IN NUMBER,
    p_order_id    OUT NUMBER
)
IS
BEGIN
        p_order_id := orders_seq.NEXTVAL;
    
    INSERT INTO orders(id, table_id, customer_id, status)
    VALUES(p_order_id, p_table_id, p_customer_id, 'pending');
    
       UPDATE restaurant_tables 
    SET status = 'occupied'
    WHERE id = p_table_id;
    
    COMMIT;
END;
/

-- To generate bill

CREATE OR REPLACE PROCEDURE generate_bill(
    p_order_id IN NUMBER,
    p_discount IN NUMBER DEFAULT 0,
    p_method   IN VARCHAR2 DEFAULT 'cash'
)
IS
    v_subtotal NUMBER;
    v_vat      NUMBER;
    v_total    NUMBER;
BEGIN
       v_subtotal := get_order_subtotal(p_order_id);
    
       v_vat   := v_subtotal * 0.15;
    v_total := v_subtotal + v_vat - p_discount;
    
        INSERT INTO payments(id, order_id, subtotal, vat, discount, total, method)
    VALUES(payments_seq.NEXTVAL, p_order_id, v_subtotal, v_vat, p_discount, v_total, p_method);
    
       UPDATE orders 
    SET status = 'paid'
    WHERE id = p_order_id;
    
    COMMIT;
END;
/