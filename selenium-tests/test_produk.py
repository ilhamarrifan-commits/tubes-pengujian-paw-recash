import time

from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select

from base_test import BaseTest


class TestProduk(BaseTest):

    def setUp(self):
        super().setUp()

        self.login(
            "adminwulan@gmail.com",
            "12345678"
        )

    def test_01_tambah_produk(self):

        self.driver.get(
            "http://127.0.0.1:8000/manager/products/create"
        )

        time.sleep(2)

        # Product Name
        self.driver.find_element(
            By.NAME,
            "name"
        ).send_keys("Produk Selenium")

        # Category
        category = Select(
            self.driver.find_element(
                By.NAME,
                "category_id"
            )
        )

        category.select_by_visible_text("Meals")

        # Price
        self.driver.find_element(
            By.NAME,
            "price"
        ).send_keys("10000")

        # Stock
        stock = self.driver.find_element(
            By.NAME,
            "stock"
        )

        stock.clear()
        stock.send_keys("50")

        time.sleep(2)

        # Submit
        self.driver.find_element(
            By.CSS_SELECTOR,
            "button[type='submit']"
        ).click()

        time.sleep(3)

        self.assertIn(
            "Product created successfully",
            self.driver.page_source
        )
