<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte de fidélité</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            height: 100vh;
            width: 100vw;
            font-family: 'Roboto', sans-serif;
            text-align: center;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 350px;
            height: 520px;
            background-color: #fff;
            border-radius: 7px;
            box-shadow: 0 0 50px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            align-items: center;
        }
        #pattern1 {
            width: 160%;
            height: 75%;
            object-fit: cover;
            position: absolute;
            top: -20%;
            left: -10%;
            z-index: 1;
        }
        #pattern2 {
            width: 100%;
            height: 35%;
            object-fit: cover;
            position: absolute;
            top: 50%;
            z-index: 2;
        }
        .title {
            font-size: 25px;
            color: #be53fc;
            font-weight: bold;
            position: absolute;
            top: 7%;
            z-index: 3;
        }
        .avatar {
            width: 134px;
            height: 134px;
            border-radius: 50%;
            background-color: black;
            overflow: hidden;
            margin-bottom: 15px;
            position: absolute;
            top: 20%;
            z-index: 4;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .name {
            position: absolute;
            top: 45%;
            font-size: 20px;
            font-weight: 600;
            color: #404040;
            z-index: 5;
        }
        .qr-code {
            width: 160px;
            height: 160px;
            position: absolute;
            top: 75%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            color: #fff;
            font-size: 16px;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 0 25px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="card">
        <img id="pattern1" src="" alt="Pattern waves background">
        <img id="pattern2" src="" alt="Geometric background">
        <span class="title">Carte De Fidélité</span>
        <div class="avatar">
            <img src="data:image/png;base64,{{ $tab['qrcode'] }}" alt="Avatar du client">
        </div>
        <span class="name">{{ $tab['client'] }}</span>
        <div class="qr-code">
            <img src="data:image/png;base64,{{ $tab['image'] }}" alt="QR Code">
        </div>
    </div>
</body>
</html>
