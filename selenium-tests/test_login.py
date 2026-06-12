from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC

from base_test import BaseTest
from config import BASE_URL


class TestLogin(BaseTest):

    def test_01_login_berhasil(self):

        self.login(
            "adminwulan@gmail.com",
            "12345678"
        )

        self.wait.until(
            EC.url_contains("dashboard")
        )

        self.assertIn(
            "dashboard",
            self.driver.current_url.lower()
        )

    def test_02_login_gagal(self):

        self.driver.get(f"{BASE_URL}/login")

        self.driver.find_element(
            By.NAME,
            "email"
        ).send_keys("salah@gmail.com")

        self.driver.find_element(
            By.NAME,
            "password"
        ).send_keys("salah123")

        self.driver.find_element(
            By.CSS_SELECTOR,
            "button[type='submit']"
        ).click()

        self.assertIn(
            "/login",
            self.driver.current_url
        )
