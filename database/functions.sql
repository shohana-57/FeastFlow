-- vat calculation

CREATE OR REPLACE FUNCTION calculate_total(
    p_subtotal IN NUMBER,
    p_discount IN NUMBER DEFAULT 0
) RETURN NUMBER 
IS
    v_vat NUMBER;
    v_total NUMBER;
BEGIN
    v_vat := p_subtotal * 0.15;  -- 15% VAT
    v_total := p_subtotal + v_vat - p_discount;
    RETURN v_total;
END;
/

-- without vat order cost

CREATE OR REPLACE FUNCTION get_order_subtotal(
    p_order_id IN NUMBER
) RETURN NUMBER 
IS
    v_subtotal NUMBER := 0;
BEGIN
    SELECT SUM(quantity * unit_price) 
    INTO v_subtotal
    FROM order_items
    WHERE order_id = p_order_id;
    
    RETURN v_subtotal;
END;
/