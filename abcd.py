# import requests

# headers = {
#     'accept': '*/*',
#     'accept-language': 'en-US,en;q=0.9',
#     'content-type': 'application/json',
#     'origin': 'https://sidgeminiai.siddharthabank.com',
#     'priority': 'u=1, i',
#     'referer': 'https://sidgeminiai.siddharthabank.com/',
#     'sec-ch-ua': '"Not(A:Brand";v="8", "Chromium";v="144", "Microsoft Edge";v="144"',
#     'sec-ch-ua-mobile': '?0',
#     'sec-ch-ua-platform': '"Windows"',
#     'sec-fetch-dest': 'empty',
#     'sec-fetch-mode': 'cors',
#     'sec-fetch-site': 'cross-site',
#     'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0',
#     'x-goog-api-client': 'google-genai-sdk/1.32.0 gl-node/web',
#     'x-goog-api-key': 'AIzaSyAyNxju-MfI_lM5ckA81fO3z-EReHm1qsg',
# }

# json_data = {
#     'contents': [
#         {
#             'parts': [
#                 {
#                     'text': 'HI',
#                 },
#             ],
#             'role': 'user',
#         },
#     ],
#     'systemInstruction': {
#         'parts': [
#             {
#                 'text': 'You are ABCD-Agent — the official, intelligent digital assistant of ABCD Bank Limited.\n\nYou operate as a verified, professional, and compliant virtual representative of the Bank, designed to interact with users in a trustworthy, respectful, and informative manner.\n\nYour only authorized knowledge source is the official Siddhartha Bank website:\nhttps://www.abcd.com/ \nand all of its directly linked and officially maintained subpages.\n\nYou are strictly prohibited from accessing or using any information not derived from the abcd Bank website. \nAll responses must remain within the scope of publicly available information provided by the Bank.\n\n### 🎯 **Core Purpose**\nYour primary mission is to:\n1. Provide customers, staff, and visitors with accurate, clear, and verified information regarding abcd Bank’s services, products, digital offerings, branch details, corporate information, policies, and announcements.\n2. Enhance user experience by answering questions directly sourced from official pages.\n3. Maintain strict adherence to the Bank’s data governance, confidentiality, and compliance framework.\n4. Serve as a factual, non-opinionated, non-speculative knowledge agent for abcd Bank.\n\n### ⚙️ **Operational Principles**\n1. **Information Authenticity**\n   - Only reference information found on abcd Bank’s official domain and its subpages.\n   - Never assume, predict, or fabricate any data, offer, or policy.\n   - Always present factual and current details, verifying consistency with official content tone and structure.\n\n2. **Compliance & Security**\n   - Comply with banking data protection, privacy, and regulatory obligations.\n   - Never store, log, or expose sensitive customer data (e.g., account numbers, balances, transaction details, credentials).\n   - Avoid processing any form of confidential, proprietary, or internal information.\n   - Respect NRB (Nepal Rastra Bank) regulations, ISO/IEC 27001 information security principles, and financial sector data handling standards.\n\n3. **Professional Conduct**\n   - Maintain a tone of formality, politeness, and clarity consistent with professional banking communication.\n   - Ensure that all interactions reinforce abcd Bank’s brand values: trust, reliability, and transparency.\n   - Never express personal opinions, biases, or emotional commentary.\n\n4. **Limited Scope & Refusal Policy**\n   - If the question requires information not found on abcd Bank’s official site, respond with:\n     “I’m sorry, but I can only provide information available on abcd Bank’s official website. \n     Please visit https://www.abcdbank.com/ for verified details.”\n   - If a user asks for internal, financial, or confidential matters (e.g., staff details, system architecture, or internal decisions), firmly and politely decline.\n   - Never provide investment, legal, or financial advice.\n\n### 🏦 **Response Guidelines**\n1. **Tone & Language**\n   - Use clear, formal, grammatically correct English.\n   - Avoid slang, abbreviations, or informal expressions.\n   - Remain neutral and factual at all times.\n\n2. **Structure**\n   - Begin responses courteously (“Certainly.” / “Here’s what I found on abcd Bank’s official website.”).\n   - Present answers in short, readable sections or bullet points.\n   - Conclude with a professional closing such as:\n     “For more details, please visit the abcd Bank official website.”\n     or\n     “You may explore additional information at https://www.abcdbank.com/.”\n',
#             },
#         ],
#         'role': 'user',
#     },
#     'tools': [
#         {
#             'googleSearch': {},
#         },
#     ],
#     'generationConfig': {},
# }

# response = requests.post(
#     'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
#     headers=headers,
#     json=json_data,
# )
# print(response.content)
# # while True:
# #     response = requests.post('https://api.dify.ai/v1/chat-messages', headers=headers, json=json_data)
# #     print(response.content)
    
# # Note: json_data will not be serialized by requests
# # exactly as it was in the original request.
# #data = '{"inputs":{},"query":"hello","response_mode":"streaming","user":"wordpress-user-vrl8en01jvq"}'
# #response = requests.post('https://api.dify.ai/v1/chat-messages', headers=headers, data=data)



# from google import genai

# client = genai.Client(
#     api_key="AIzaSyAyNxju-MfI_lM5ckA81fO3z-EReHm1qsg"
# )

# print("Gemini chatbot 🤖 (type 'exit' to quit)")

# while True:
#     user_input = input("You: ")
#     if user_input.lower() == "exit":
#         break

#     response = client.models.generate_content(
#         model="gemini-3-flash-preview",
#         contents=user_input
#     )

#     print("Bot:", response.text)






# import google.generativeai as genai
# import os

# # 1. Setup your API Key
# os.environ["GOOGLE_API_KEY"] = "AIzaSyAyNxju-MfI_lM5ckA81fO3z-EReHm1qsg"
# genai.configure(api_key=os.environ["GOOGLE_API_KEY"])

# def check_usage_and_call():
#     model = genai.GenerativeModel('gemini-1.5-flash')
    
#     prompt = "Explain quantum physics in one sentence."
    
#     # Optional: Pre-calculate tokens before sending (to save money/quota)
#     token_count_request = model.count_tokens(prompt)
#     print(f"Estimated Input Tokens: {token_count_request.total_tokens}")

#     try:
#         response = model.generate_content(prompt)
        
#         # 2. Get real usage metadata from the response
#         usage = response.usage_metadata
#         print("-" * 30)
#         print(f"Actual Prompt Tokens: {usage.prompt_token_count}")
#         print(f"Actual Response Tokens: {usage.candidates_token_count}")
#         print(f"Total Tokens for this call: {usage.total_token_count}")
#         print("-" * 30)
        
#         # 3. Reference for Rate Limits (Based on Gemini 1.5 Flash - Free Tier)
#         # Note: These values must be manually updated based on your tier
#         # Free Tier: 15 RPM, 1 million TPM, 1,500 RPD
#         print("Note: Check your specific tier limits at https://aistudio.google.com/app/plan")
        
#     except Exception as e:
#         if "429" in str(e):
#             print("Rate Limit Exceeded! (Resource Exhausted)")
#         else:
#             print(f"An error occurred: {e}")

# if __name__ == "__main__":
#     check_usage_and_call()



from google import genai
from google.api_core.exceptions import ResourceExhausted, TooManyRequests
import time

client = genai.Client(api_key="AIzaSyAyNxju-MfI_lM5ckA81fO3z-EReHm1qsg")

count = 0

while True:
    try:
        response = client.models.generate_content(
            model="gemini-3-flash-preview",
            contents="Say hello in one word"
        )
        count += 1
        print(f"Request #{count} OK")

        # optional: tiny delay to avoid instant RPM lock
        time.sleep(0.1)

    except TooManyRequests:
        print("\n❌ Rate limit hit (429)")
        break

    except ResourceExhausted:
        print("\n❌ Quota exhausted")
        break

    except Exception as e:
        print("\n❌ Other error:", e)
        break

print(f"\nTotal successful requests: {count}")
