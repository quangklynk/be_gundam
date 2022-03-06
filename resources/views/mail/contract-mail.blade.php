<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Mail</title>
    <style>
        .container-header {
            color: rgb(36, 36, 36);
        }

        .container-main {
            color: rgb(36, 36, 36);
        }
        .container-main ul {
            list-style-type: disclosure-closed;
        }
    </style>
</head>
<body>   
    <section>
        <header class="container-header">
            <h1>HOBBY STORE</h1>
        </header>
        <main class="container-main">
            <h3>Mail cộng tác của khách hàng</h3>
            <ul>
                <li>
                    <p>Họ Tên: {{$details['name']}}</p>
                </li>
                <li>
                    <p>Email: {{$details['email']}}</p>
                </li>
                <li>
                    <p>
                        Nội dung: {{$details['content']}}
                    </p>
                </li>
            </ul>
            <p>Trân trọng!</p>
        </main>
    </section>
</body>
</html>