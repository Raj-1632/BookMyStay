<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login Panel</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .login-header {
            background: #333;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
        }
        select{
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input{
            width: 92%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-btn:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">MANAGER LOGIN PANEL</div>
        <form action="manager_login.php" method="POST">
            <select name="property_type">
                <option value="hotel">Hotel</option>
                <option value="resort">Resort</option>
                <option value="villa">Villa</option>
            </select>
            <input type="text" name="property_id" placeholder="Enter Property ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-btn">LOGIN</button>
        </form>
    </div>
</body>
</html>