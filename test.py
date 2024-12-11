from linebot import LineBotApi
from linebot.models import TextMessage

# 替換為您的 Channel Access Token
access_token = 'z98QoS1YwD9n+VvDr6lJurWEv/5R0ekFSYisBGWtPff8NrQkpi1HcFa+J/ZiTujY8YYetCxTH+WOIv2BZ+ual43T+iwgjeJni3KFMgSdniKuY64fYbIooBs9ebKzaJDcMzf3n1uE33lWxWR5s2M1VwdB04t89/1O/w1cDnyilFU='

# 初始化 LineBotApi
line_bot_api = LineBotApi(access_token)

# 傳送訊息
user_id = 'U4823a2003ba2449fb455da89af1bad6d'  # 替換為實際的 User ID
message = TextMessage(text='你好！我是你的健忘症小幫手～')

try:
    line_bot_api.push_message(user_id, message)
    print("Message sent successfully!")
except Exception as e:
    print(f"Failed to send message: {e}")
