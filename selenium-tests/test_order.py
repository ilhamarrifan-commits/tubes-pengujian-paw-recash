# test_order.py
import time
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from base_test import BaseTest
from config import KASIR_EMAIL, KASIR_PASSWORD, BASE_URL

class TestPembuatanOrder(BaseTest):
    def setUp(self):
        super().setUp()
        self.login(KASIR_EMAIL, KASIR_PASSWORD)

    def test_01_proses_order_sampai_berhasil(self):
        self.driver.get(f"{BASE_URL}/cashier/dashboard")

        self.driver.find_element(By.XPATH, "//*[contains(text(), 'Jus Melon')]").click()
        time.sleep(1)

        self.driver.find_element(By.ID, "customerName").send_keys("Robot Selenium")
        self.driver.find_element(By.XPATH, "//*[contains(text(), 'Dine In')]").click()
        self.driver.find_element(By.XPATH, "//button[contains(., 'Confirm Order')]").click()

        self.wait.until(EC.alert_is_present())
        alert = self.driver.switch_to.alert
        alert.accept()
        time.sleep(1)

        self.driver.find_element(By.XPATH, "//a[contains(., 'History')] | //button[contains(., 'History')]").click()

        self.wait.until(EC.url_contains("/history"))
        self.assertIn('/history', self.driver.current_url)
        self.assertIn('Paid', self.driver.page_source)
