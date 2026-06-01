CREATE TABLE users (
    id          NUMBER PRIMARY KEY,
    name        VARCHAR2(100) NOT NULL,
    email       VARCHAR2(100) NOT NULL UNIQUE,
    password    VARCHAR2(255) NOT NULL,
    role        VARCHAR2(20) DEFAULT 'customer' 
                CHECK (role IN ('admin','manager','waiter','customer')),
    created_at  DATE DEFAULT SYSDATE
);
CREATE SEQUENCE users_seq
START WITH 1
INCREMENT BY 1;

CREATE TABLE categories (
    id          NUMBER PRIMARY KEY,
    name        VARCHAR2(100) NOT NULL UNIQUE,
    description VARCHAR2(255)
);

CREATE SEQUENCE categories_seq
START WITH 1
INCREMENT BY 1;

CREATE TABLE menu_items (
    id          NUMBER PRIMARY KEY,
    category_id NUMBER NOT NULL,
    name        VARCHAR2(100) NOT NULL,
    price       NUMBER(10,2) NOT NULL CHECK (price > 0),
    description VARCHAR2(255),
    status      VARCHAR2(20) DEFAULT 'available'
                CHECK (status IN ('available','unavailable')),
    image       VARCHAR2(255),
    CONSTRAINT fk_menu_category 
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE SEQUENCE menu_items_seq
START WITH 1
INCREMENT BY 1;

CREATE TABLE restaurant_tables (
    id           NUMBER PRIMARY KEY,
    table_number NUMBER NOT NULL UNIQUE,
    capacity     NUMBER NOT NULL CHECK (capacity > 0),
    status       VARCHAR2(20) DEFAULT 'free'
                 CHECK (status IN ('free','occupied','reserved'))
);

CREATE SEQUENCE restaurant_tables_seq
START WITH 1
INCREMENT BY 1;

CREATE TABLE orders (
    id          NUMBER PRIMARY KEY,
    table_id    NUMBER NOT NULL,
    customer_id NUMBER NOT NULL,
    status      VARCHAR2(20) DEFAULT 'pending'
                CHECK (status IN ('pending','preparing','ready','paid')),
    created_at  DATE DEFAULT SYSDATE,
    CONSTRAINT fk_order_table 
    FOREIGN KEY (table_id) REFERENCES restaurant_tables(id),
    CONSTRAINT fk_order_customer 
    FOREIGN KEY (customer_id) REFERENCES users(id)
);

CREATE SEQUENCE orders_seq
START WITH 1
INCREMENT BY 1;

CREATE TABLE order_items (
    id           NUMBER PRIMARY KEY,
    order_id     NUMBER NOT NULL,
    menu_item_id NUMBER NOT NULL,
    quantity     NUMBER NOT NULL CHECK (quantity > 0),
    unit_price   NUMBER(10,2) NOT NULL,
    CONSTRAINT fk_orderitem_order 
    FOREIGN KEY (order_id) REFERENCES orders(id),
    CONSTRAINT fk_orderitem_menu 
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

CREATE SEQUENCE order_items_seq
START WITH 1
INCREMENT BY 1;


CREATE TABLE payments (
    id       NUMBER PRIMARY KEY,
    order_id NUMBER NOT NULL UNIQUE,
    subtotal NUMBER(10,2) NOT NULL,
    vat      NUMBER(10,2) DEFAULT 0,
    discount NUMBER(10,2) DEFAULT 0,
    total    NUMBER(10,2) NOT NULL,
    method   VARCHAR2(20) DEFAULT 'cash'
             CHECK (method IN ('cash','card')),
    paid_at  DATE DEFAULT SYSDATE,
    CONSTRAINT fk_payment_order 
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE SEQUENCE payments_seq
START WITH 1
INCREMENT BY 1;


CREATE TABLE staff (
    id        NUMBER PRIMARY KEY,
    user_id   NUMBER NOT NULL UNIQUE,
    position  VARCHAR2(100) NOT NULL,
    shift     VARCHAR2(20) 
              CHECK (shift IN ('morning','evening','night')),
    salary    NUMBER(10,2) CHECK (salary > 0),
    join_date DATE DEFAULT SYSDATE,
    CONSTRAINT fk_staff_user 
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE SEQUENCE staff_seq
START WITH 1
INCREMENT BY 1;


CREATE TABLE inventory (
    id              NUMBER PRIMARY KEY,
    ingredient_name VARCHAR2(100) NOT NULL UNIQUE,
    quantity        NUMBER(10,2) NOT NULL CHECK (quantity >= 0),
    unit            VARCHAR2(20) NOT NULL,
    min_stock       NUMBER(10,2) DEFAULT 10,
    updated_at      DATE DEFAULT SYSDATE
);

CREATE SEQUENCE inventory_seq
START WITH 1
INCREMENT BY 1;


CREATE TABLE menu_item_ingredients (
    id            NUMBER PRIMARY KEY,
    menu_item_id  NUMBER NOT NULL,
    inventory_id  NUMBER NOT NULL,
    quantity_needed NUMBER(10,2) NOT NULL,
    CONSTRAINT fk_ingredient_menu 
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id),
    CONSTRAINT fk_ingredient_inventory 
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

CREATE SEQUENCE menu_item_ingredients_seq
START WITH 1
INCREMENT BY 1;



CREATE SEQUENCE feedback_seq
START WITH 1
INCREMENT BY 1;

CREATE TABLE feedback (
    id           NUMBER PRIMARY KEY,
    customer_id  NUMBER NOT NULL,
    menu_item_id NUMBER NOT NULL,
    rating       NUMBER,
    remarks      VARCHAR2(500),
    created_at   DATE DEFAULT SYSDATE
);

drop table feedback;

CREATE TABLE feedback (
    id           NUMBER PRIMARY KEY,
    customer_id  NUMBER NOT NULL,
    menu_item_id NUMBER NOT NULL,
    rating       NUMBER CHECK (rating BETWEEN 1 AND 5),
    remarks      VARCHAR2(500),
    created_at   DATE DEFAULT SYSDATE,
    CONSTRAINT fk_feedback_customer 
    FOREIGN KEY (customer_id) REFERENCES users(id),
    CONSTRAINT fk_feedback_menu 
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);


SELECT table_name FROM user_tables ORDER BY table_name;




