# WH interview-task

## Task Instructions
### Task 1 - Start the project
Run the following command to start the project:
```bash
cp .env.example .env
docker-compose up
docker exec -i [container_name] composer install  
```

### Task 1 - Perform Code Review
Review the code leaving comments as TODO items

### Task 2 - Refactoring
Fix 3 main issues you noticed during code review

### Task 3 - Convert api to json format
Currently api is returning responses using plain text, convert to json format

### Task 4 - Order creation returns full order
Full order details should be returned on creation, lets improve it. 

### Task 5 - Secure api
Currently api does not use api key, lets secure it.

### Endpoints

- POST /api/order
    ```json
    {
      "customer_id": 1,
      "total_amount": 200
    }
    ```
- GET /api/order/details
