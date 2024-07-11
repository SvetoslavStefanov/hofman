# Author
- email: svetliooo@gmail.com
- Name: Svetoslav Stefanov (Svet)

# Initial setup
1. ``` git clone https://github.com/SvetoslavStefanov/hofman.git```
2. ``` cd hofman```
3. ``` composer install ```
4. ``` php artisan migrate ```
5. ``` php artisan serve ```

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