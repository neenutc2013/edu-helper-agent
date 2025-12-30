<!DOCTYPE html>
<html>
<head>
    <title>EduHelper Chat</title>

    <style>
        body { font-family: Arial; background: #f0f2f5; }
        .chat-container { width: 400px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        .messages { height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
        .message { margin-bottom: 10px; }
        .user { text-align: right; color: #333; }
        .agent { text-align: left; color: #fff; background: #007bff; padding: 5px 10px; border-radius: 5px; display: inline-block; }
        input[type=text] { width: 75%; padding: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { padding: 10px 15px; border-radius: 5px; border: none; background: #007bff; color: #fff; cursor: pointer; }
    </style>
</head>
<body>

<div class="chat-container">
  <h2>Edu Helper Agent</h2>
    <div class="messages" id="chatMessages"></div>
    <input type="text" id="userMessage" placeholder="Type your message..." />
    <button onclick="sendMessage()">Send</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function sendMessage() {
    var message = $('#userMessage').val();
    if(!message) return;

    $('#chatMessages').append('<div class="message user">'+message+'</div>');
    $('#userMessage').val('');

    $.post('<?php echo site_url("chat/send_message"); ?>', {message: message}, function(data){
        var response = JSON.parse(data);
        $('#chatMessages').append('<div class="message agent">'+response.reply+'</div>');
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    });
}
</script>

</body>
</html>
