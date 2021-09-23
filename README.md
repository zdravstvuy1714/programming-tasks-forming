### Application for forming a selection of programming tasks for every day.

####Specifications:
- PHP 8.0
- MySQL 5.7

####Application launch:
1. Configure .env file.
2. run artisan command 'php artisan migrate --seed' (Run migrations and add categories).

####Postman request config:
- Authorization - bearer token
- Headers - Accept => application/json
- Body - x-www-form-urlencoded

####API capabilities:
##### Authentication
- Registration **(POST: api/registration)**
    - Important fields (name, email, password, password_confirmation, device_name).
    - A token should be returned.
    - When registering, tasks are generated for the user automatically.
- Logout **(POST: api/logout)**
    - Need an auth token.
- Login **(POST: api/login)**
    - Important fields (email, password, device_name).
    - A token should be returned.
    
##### Tasks
- Daily\current user tasks **(GET: api/tasks)**
    - Tasks from each category will return for today.
    - Task status is determined by the is_complete attribute.
- Mark the task as completed **(POST: api/tasks/{id}/complete)**
    - You will not be able to mark a completed task that is not in your current tasks.
- Skip task **(POST: api/tasks/{id}/skip)**
    - You will not be able skip task that is not in your current tasks.
    
#### How to start generating tasks for all users every 24 hours?
To generate tasks every 24 hours, run the command: **'php artisan schedule:work'**

(If you want to generate new tasks now, run the command: **'php artisan update:current:tasks'**)

