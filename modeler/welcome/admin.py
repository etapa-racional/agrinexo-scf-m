from django.contrib import admin
from django.template.response import TemplateResponse
from .models import PageView

class CustomAdminSite(admin.ModelAdmin):
    site_header = "Custom Admin Site header"
    site_title = "Custom Admin Site title"

    def get_urls(self): # 2.
        urls = super().get_urls()
        my_urls = [
            path('custom_admin_view/',
                self.admin_view(( # 3.
                CustomAdminView.as_view(admin_site=self))), name='cav'),
        ]
        return my_urls + urls # 4.


admin_site = CustomAdminSite(name='myadmin') # 5.
admin_site.register(MyModel)

class PageViewAdmin(admin.ModelAdmin):
    list_display = ['hostname', 'timestamp']

admin.site.register(PageView, PageViewAdmin)
