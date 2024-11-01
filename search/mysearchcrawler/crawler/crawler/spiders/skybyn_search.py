import scrapy


class SkybynSearchSpider(scrapy.Spider):
    name = "skybyn_search"
    allowed_domains = ["itavisen.no"]
    start_urls = ["https://itavisen.no"]

    def parse(self, response):
        pass
