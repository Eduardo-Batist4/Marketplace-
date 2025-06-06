
# Rotas


### **Login e Registro**

// Não é necessário autenticação.

**Post:** /login
```bash
    {
        "email": "email@teste.com",
        "password": "senha123"
    }
```
**Post:** /register
```bash
    {
        "name": "Jhon Doe",
        "email": "email@teste.com",
        "password": "senha123",
    }

// Com imagem, o formato será Form Data (Multipart).

    {
        name                Jhon Doe
        email               email@teste.com
        password            senha123
        image_path          file
    }
```

## User
**POST:** /users/{id}/update-image
```bash
// Atualiza a foto do usuario.

    name = image_path
    value = file
```
**GET:** /users/{id}
```bash
// Retorna o usuário logado.
```
**PUT:** /users/{id}
```bash
// Somente o dono do perfil pode atualizar.

    {
        "name": "Jhon Doe 2",
        "email": "email@updated.com",
    }
```
**DELETE:** /users/{id}
```bash
// Apaga usuario.
```

## Address
**GET:** /addresses
```bash
// Retorna todos os endereços do usuário.
```
**POST:** /addresses
```bash
    {
        "street": "Sunset Boulevard",
        "number": 1200,
        "zip": "90026",
        "city": "Los Angeles",
        "state": "California",
        "country": "United States"
    }
```
**PUT:** /addresses/{id}
```bash
// Não é obrigatório preencher todos os campos.

    {
        "street": "Sunset Boulevard 2",
        "number": 1202,
        "zip": "90027",
        "city": "Los Angeles 2",
        "state": "California 2",
        "country": "United States 2"
    }
```
**DELETE:** /addresses/{id}
```bash
// Apaga um endereço.
```
## Category
// Somente Admin pode criar/atualizar e deletar uma categoria.

**GET:** /categories 
```bash
// Não é necessário autenticação.
// Retorna todos as categorias.
```
**POST:** /categories 
```bash
    {
        "name": "Eletronico",
        "description": "Tv, Video-game, Celular, Eletrodomésticos, Computadores e Notebooks."
    }
```
**GET:** /categories/{id}
```bash
// Não é necessário autenticação.
// Retorna uma categoria.
```
**PUT:** /categories/{id}
```bash
// Não é obrigatório preencher todos os campos.

    {
        "name": "Roupas",
        "description": "Calças, Camisetas, Jaquetas, Blusas, Intimas e Bermudas"
    }
```
**DELETE:** /categories/{id}
```bash
// Apaga uma categoria.
```
## Product
// Somente Admin e Moderadores podem criar/atualizar e deletar um produto.

**GET:** /products 
```bash
// Não é necessário autenticação.
// Retorna todos os produtos.
// Com Filtros:
    /products?name=nome 
    /products?category=category_id 
    /products?min_price=500 
    /products?max_price=1000 
```
**POST:** /products 
```bash
// Como usamos imagem, o formato será Form Data (Multipart).

    {
        name                Tv Samsung
        price               4800
        stock               100
        category_id         1
        image_path          file
        description         42 Polegadas....
    }
```
**GET:** /products/{id}
```bash
// Não é necessário autenticação.
// Retorna um produto.
```
**PUT:** /products/{id}
```bash
// Não é obrigatório preencher todos os campos.
// Se for atualizar a imagem, ultilizar o formato Multipart.

    {
        "name": "Tv Tcl",
        "price": 5800,
        "stock": 150,
        "category_id: 2,
        "description: "64 Polegadas..."
    }
```
**DELETE:** /products/{id}
```bash
// Apaga um produto.
```
## Discount
// Somente Admin pode resgatar/criar/atualizar e deletar um desconto.

**GET:** /discounts 
```bash
// Retorna todos os descontos.
```
**POST:** /discounts 
```bash
    {
        "description": "Desconto 50",
        "discount_percentage": 50,
        "start_date": "2025-04-10",
        "end_date": "2025-04-19",
        "product_id": 4
    }
```
**GET:** /discounts/{id}
```bash
// Retorna um desconto.
```
**PUT:** /discounts/{id}
```bash
// Não é obrigatório preencher todos os campos.

    {
        "description": "Desconto Fallen GOAT",
        "discount_percentage": 60,
        "start_date": "2025-04-11",
        "end_date": "2025-04-16",
        "product_id": 3
    }
```
**DELETE:** /discounts/{id}
```bash
// Apaga um desconto.
```
## Coupon
// Somente Admin pode resgatar/criar/atualizar e deletar um cupom.

**GET:** /coupons 
```bash
// Retorna todos os cupons.
```
**POST:** /coupons 
```bash
    {
        "code": "MOAC45",
        "discount_percentage": 45,
        "start_date": "2025-06-01",
        "end_date": "2025-06-04"
    }
```
**GET:** /coupons/{id}
```bash
// Retorna um desconto.
```
**PUT:** /coupons/{id}
```bash
// Não é obrigatório preencher todos os campos.

    {
        "code": "MOAC45",
        "discount_percentage": 45,
        "start_date": "2025-06-01",
        "end_date": "2025-06-04"
    }
```
**DELETE:** /coupons/{id}
```bash
// Apaga um desconto.
```
## Cart_item

**GET:** /cart_items 
```bash
// Retorna todos os itens do carrinho.
```
**POST:** /cart_items 
```bash
    {
        "product_id": 6,
        "quantity": 2
    }
```
**PUT:** /cart_items/{id}
```bash
    {
        "quantity": 2
    }
```
**DELETE:** /cart_items/{id}
```bash
// Apaga um item do carrinho.
```
## Order
// Ao criar o pedido, todos os itens do carrinho são automaticamente puxados para o order_items.
// Para atualizar o status do pedido, somente Admin e Moderadores.

**GET:** /orders 
```bash
// Retorna todos os pedidos do usuário.
```
**GET:** /orders/everyone
```bash
// Somente admin.
// Retorna todos os pedidos.
```
**POST:** /orders 
```bash
    {
	    "address_id": 5
    }
```
**PUT:** /orders/{id}
```bash
    {
        "status": "completed"
    }
```
**DELETE:** /orders/{id}
```bash
// Apaga um pedido.
```
## Feedback

**GET:** /feedbacks/{id}/all
```bash
// Não é necessário autenticação.
// Retorna todos os feedbacks do produto.
```
**GET:** /feedbacks/{id}
```bash
// Não é necessário autenticação.
// Retorna um único feedback do produto.
``` 
**POST:** /feedbacks
```bash
// Usando imagem, o formato será Form Data (Multipart).

    {
        product_id          2
        stars               5
        image_path          file
        description         descrição....
    }
```
**PUT:** /feedbacks/{id}
```bash
// Permitido somente a descrição.

    {
        "description": "descrição...."
    }
```
**DELETE:** /feedbacks/{id}
```bash
// Somente Moderador e Admin
// Apaga um endereço.
```
## Forgot and Reset Password

// Não é necessário autenticação.

1. Acessar a rota /forgot-password:
```bash
    {
        "email": "email@teste.com"
    }
```
2. Ir para o MailHog:
```bash
    // Extrair o token do link recebido.
    // Exemplo:
    // reset_password/**token00203002302032**
```
3. Acessar a rota /reset-password:
```bash
    {
        "email": "email@teste.com",
        "password": "senha",
        "password_confirmation": "senha",
        "token": "token00203002302032"
    }
```