import unittest
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
from config import BASE_URL


class BaseTest(unittest.TestCase):

    def setUp(self):
        options = webdriver.ChromeOptions()

        # Uncomment jika ingin headless
        # options.add_argument("--headless=new")

        options.add_argument("--window-size=1280,720")

        self.driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=options
        )

        self.driver.implicitly_wait(5)
        self.wait = WebDriverWait(self.driver, 10)

    def tearDown(self):
        self.driver.quit()

    def login(self, email, password):

        login_url = f"{BASE_URL}/login"

        print(f"\nMembuka URL: {login_url}")

        self.driver.get(login_url)

        print("Current URL :", self.driver.current_url)
        print("Page Title  :", self.driver.title)

        try:
            email_input = self.wait.until(
                EC.presence_of_element_located((By.NAME, "email"))
            )

            password_input = self.driver.find_element(By.NAME, "password")

            email_input.clear()
            email_input.send_keys(email)

            password_input.clear()
            password_input.send_keys(password)

            self.driver.find_element(
                By.CSS_SELECTOR,
                "button[type='submit']"
            ).click()

            self.wait.until(EC.url_contains("dashboard"))

        except Exception as e:
            print("\n=== DEBUG HTML PAGE ===")
            print(self.driver.page_source[:3000])
            print("\n=== END DEBUG HTML PAGE ===")
            raise e
