<html>
<head>
    <title>Ganado</title>
</head>
<body>
       Hello <p> {{$data['mail']}}</p>
       <br/>

       <p>
             The Order with this tracking number <br/>
               {{$data['tracking_number']}} 
            was canceled by {{$data['userName']}}
       </p>
        
    <p>Thank you</p>
</body>
</html>