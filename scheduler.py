from apscheduler.schedulers.background import BackgroundScheduler
import requests
import time

def send_task_reminders():
    # Fetch tasks that are due soon or pending
    # For simplicity, assume there's an API endpoint in Laravel that returns such tasks
    response = requests.get('http://localhost:8000/api/tasks/reminders')  # Replace with actual URL
    if response.status_code == 200:
        tasks = response.json()
        for task in tasks:
            # Send reminder via Webhook.site or SendGrid
            send_email(task)

def send_email(task):
    mailjet_api_key = '33d777801cf75410f2357a127300f924'
    from_email = 'no-reply@jjtms.com'
    to_email = task['assignee_email']
    subject = f"Reminder: Task '{task['title']}' is pending"
    content = f"Hello {task['assignee_name']},\n\nThis is a reminder that the task '{task['title']}' is pending.\n\nBest,\nJJTMS"

    data = {
        "personalizations": [{
            "to": [{"email": to_email}],
            "subject": subject
        }],
        "from": {"email": from_email},
        "content": [{
            "type": "text/plain",
            "value": content
        }]
    }

    response = requests.post(
        'https://api.sendgrid.com/v3/mail/send',
        json=data,
        headers={'Authorization': f'Bearer {mailjet_api_key}'}
    )

    if response.status_code == 202:
        print(f"Reminder sent to {to_email} for task '{task['title']}'")
    else:
        print(f"Failed to send reminder to {to_email}")

if __name__ == "__main__":
    scheduler = BackgroundScheduler()
    scheduler.add_job(send_task_reminders, 'interval', hours=24)  # Runs daily
    scheduler.start()
    print("Scheduler started. Press Ctrl+C to exit.")
    try:
        while True:
            time.sleep(2)
    except (KeyboardInterrupt, SystemExit):
        scheduler.shutdown()
        print("Scheduler stopped.")