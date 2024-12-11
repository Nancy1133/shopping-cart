from flask import Flask, request, abort

from linebot.v3 import (
    WebhookHandler
)
from linebot.v3.exceptions import (
    InvalidSignatureError
)
from linebot.v3.messaging import (
    Configuration,
    ApiClient,
    MessagingApi,
    ReplyMessageRequest,
    TextMessage
)
from linebot.v3.webhooks import (
    MessageEvent,
    TextMessageContent
)

app = Flask(__name__)

configuration = Configuration(access_token='z98QoS1YwD9n+VvDr6lJurWEv/5R0ekFSYisBGWtPff8NrQkpi1HcFa+J/ZiTujY8YYetCxTH+WOIv2BZ+ual43T+iwgjeJni3KFMgSdniKuY64fYbIooBs9ebKzaJDcMzf3n1uE33lWxWR5s2M1VwdB04t89/1O/w1cDnyilFU=')
handler = WebhookHandler('39d468e3acf8f2a7dc1aff9aa6c8f502')


@app.route("/callback", methods=['POST'])
def callback():
    # get X-Line-Signature header value
    signature = request.headers['X-Line-Signature']

    # get request body as text
    body = request.get_data(as_text=True)
    app.logger.info("Request body: " + body)

    # handle webhook body
    try:
        handler.handle(body, signature)
    except InvalidSignatureError:
        app.logger.info("Invalid signature. Please check your channel access token/channel secret.")
        abort(400)

    return 'OK'

from linebot import LineBotApi
from linebot.models import TextSendMessage

# 替換為您的 Channel Access Token
line_bot_api = LineBotApi('YOUR_CHANNEL_ACCESS_TOKEN')

# 傳送訊息
user_id = 'USER_ID'  # 接收者的 User ID
message = TextSendMessage(text='Hello, this is a message from LINE Bot!')
line_bot_api.push_message(user_id, message)
print("Message sent successfully!")

@handler.add(MessageEvent, message=TextMessageContent)
def handle_message(event):
    with ApiClient(configuration) as api_client:
        line_bot_api = MessagingApi(api_client)
        line_bot_api.reply_message_with_http_info(
            ReplyMessageRequest(
                reply_token=event.reply_token,
                messages=[TextMessage(text=event.message.text)]
            )
        )

if __name__ == "__main__":
    app.run()