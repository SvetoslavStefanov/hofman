# Author
- email: svetliooo@gmail.com
- Name: Svetoslav Stefanov (Svet)

# Initial setup
1. ``` git clone https://github.com/SvetoslavStefanov/hofman.git```
2. ``` cd hofman```
3. ``` composer install ```
4. ``` php artisan migrate ```
5. Copy .env.example to .env
6. Populate your `MOLLIE_KEY` & `HUBSPOT_API_KEY` to your `.env` file
7. ``` php artisan serve ```

# How to confirm payments:
1. send a post request to `http://127.0.0.1:8000/api/orders`
2. The request should contain the following data:
```json
{
      "email": "svetliooo@gmail.com",
      "order_items": [
        {
          "product_id": 2,
          "quantity": 1
        }
}
```
3. Follow the link returned in the response payment.payment_link
4. Enter this data there:
   1. Card Number: 5555 4444 3333 1111 
   2. Expiry Date: Any future date 
   3. CVC: Any 3 digits
5. Then send a POST request to simulate a webhook call from Mollie to `http://127.0.0.1:8000/api/orders/9/payment` (where `9` is your order ID)
```json
{
  "id": "tr_fcYhd69pEh",
  "amount": {
    "currency": "EUR",
    "value": "15.00"
  },
  "description": "Order 7",
  "metadata": {
    "order_id": "F2024071100507"
  },
  "status": "paid",
  "createdAt": "2024-07-11T10:32:08+00:00",
  "paidAt": "2024-07-11T10:32:08+00:00",
  "paymentUrl": "http://example.com/payment/7",
  "webhookUrl": "http://213.91.182.221:8000/api/orders/7/payment"
}
```
In this case only the `id` field matters. You could get the proper ID from Mollie's dashboard.

# Known issues
1. I wanted to send emails via a Queue in order not to slow down the request, but running things on a local environment would make it more difficult. Because of that, I made that I left the `QUEUE_CONNECTION` constant to `sync`
2. The `APP_URL` has to be a public url, like `APP_URL=http://213.91.182.221:8000`, otherwise Mollie couldn't use it as a webhook url
3. Hubspot Contact is not associated to the newly created Deal. Also a Company is not being created.

# API Endpoints
1. Create a New Order
#### url: `[POST] http://127.0.0.1:8080/api/orders`
#### Request body
```json
{
   "email": "svetliooo@gmail.com",
   "order_items": [
      {
         "product_id": 2,
         "quantity": 1
      }
   ]
}
```
#### Response body
```json
{
   "email": "svetliooo@gmail.com",
   "total_price": 99.99,
   "updated_at": "2024-07-11T14:07:42.000000Z",
   "created_at": "2024-07-11T14:07:42.000000Z",
   "id": 10,
   "ref_id": "F2024071100510",
   "items": [
      {
         "id": 14,
         "order_id": 10,
         "product_id": 2,
         "quantity": 1,
         "price": "99.99",
         "created_at": "2024-07-11T14:07:42.000000Z",
         "updated_at": "2024-07-11T14:07:42.000000Z",
         "product": {
            "id": 2,
            "name": "Product Name",
            "description": "Product Description",
            "price": "99.99",
            "sku": "unique-sku-001",
            "category": "Home",
            "deleted_at": null,
            "created_at": "2024-07-11T07:31:24.000000Z",
            "updated_at": "2024-07-11T07:31:24.000000Z"
         }
      }
   ],
   "payment": {
      "id": 5,
      "order_id": 10,
      "paid_at": null,
      "payment_link": "https:\/\/www.mollie.com\/checkout\/credit-card\/embedded\/xByanxNALB",
      "created_at": "2024-07-11T14:07:44.000000Z",
      "updated_at": "2024-07-11T14:07:44.000000Z"
   }
}
```
2. Retrieve All Orders
#### [GET] http://127.0.0.1:8080/api/orders
#### Response body
```json
[
	{
		"id": 10,
		"email": "svetliooo@gmail.com",
		"ref_id": "F2024071100510",
		"total_price": "99.99",
		"created_at": "2024-07-11T14:07:42.000000Z",
		"updated_at": "2024-07-11T14:07:42.000000Z",
		"items": [
			{
				"id": 14,
				"order_id": 10,
				"product_id": 2,
				"quantity": 1,
				"price": "99.99",
				"created_at": "2024-07-11T14:07:42.000000Z",
				"updated_at": "2024-07-11T14:07:42.000000Z",
				"product": {
					"id": 2,
					"name": "Product Name",
					"description": "Product Description",
					"price": "99.99",
					"sku": "unique-sku-001",
					"category": "Home",
					"deleted_at": null,
					"created_at": "2024-07-11T07:31:24.000000Z",
					"updated_at": "2024-07-11T07:31:24.000000Z"
				}
			}
		]
	}
]
```
3. Payment success url
#### [GET] http://127.0.0.1:8080/api/orders/{orderId}/payment
4. Confirm payment (Webhook called from Mollie)
#### [POST] http://127.0.0.1:8080/api/orders/{orderId}/payment
5. Create a New Product
#### [POST] http://127.0.0.1:8080/api/products
#### Request body
```json
{
   "name": "Paco",
   "description": "Product Description",
   "price": 20,
   "sku": "unique-sku-002",
   "category": "Home"
}
```
#### Response body
```json 
{
	"name": "Paco",
	"description": "Product Description",
	"price": 20,
	"sku": "unique-sku-002",
	"updated_at": "2024-07-11T08:07:38.000000Z",
	"created_at": "2024-07-11T08:07:38.000000Z",
	"id": 3
}
```
6. Retrieve All Products
#### [GET] http://127.0.0.1:8080/api/products
#### Response body
```json
{
   "current_page": 1,
   "data": [
      {
         "id": 1,
         "name": "Product Name",
         "description": "Product Description",
         "price": "99.99",
         "sku": "unique-sku-001",
         "category": "Home",
         "deleted_at": null,
         "created_at": "2024-07-11T07:17:08.000000Z",
         "updated_at": "2024-07-11T07:17:08.000000Z"
      }
   ],
   "first_page_url": "http:\/\/127.0.0.1:8000\/api\/products?page=1",
   "from": 1,
   "last_page": 1,
   "last_page_url": "http:\/\/127.0.0.1:8000\/api\/products?page=1",
   "links": [
      {
         "url": null,
         "label": "&laquo; Previous",
         "active": false
      },
      {
         "url": "http:\/\/127.0.0.1:8000\/api\/products?page=1",
         "label": "1",
         "active": true
      },
      {
         "url": null,
         "label": "Next &raquo;",
         "active": false
      }
   ],
   "next_page_url": null,
   "path": "http:\/\/127.0.0.1:8000\/api\/products",
   "per_page": 10,
   "prev_page_url": null,
   "to": 1,
   "total": 1
}
```
7. Retrieve a Specific Product
#### [GET] http://127.0.0.1:8080/api/products/{productId}
#### Response body
```json
{
   "id": 2,
   "name": "Product Name",
   "description": "Product Description",
   "price": "99.99",
   "sku": "unique-sku-001",
   "category": "Home",
   "deleted_at": null,
   "created_at": "2024-07-11T07:31:24.000000Z",
   "updated_at": "2024-07-11T07:31:24.000000Z"
}
```
8. Update a Specific Product
#### [PUT] http://127.0.0.1:8080/api/products/{productId}
#### Request body
```json
{
   "name": "Updated Product Name",
   "description": "Updated Product Description",
   "price": 79.99,
   "sku": "updated-unique-sku-001",
   "category": "Kitchen"
}

```
All parameters are optional
9. Delete a Specific Product
#### [DELETE] http://127.0.0.1:8080/api/products/{productId}
#### Response body
```json
{
   "message": "Product deleted successfully."
}
```