from django.contrib import admin
from .models import PageView

class CustomAdminSite(AdminSite):

    def get_urls(self):
        custom_urls = [
            path('admin/preferences/', self.admin_view(views.my_view)),
        ]
        admin_urls = super().get_urls()
        return custom_urls + admin_urls  # custom urls must be at the beginning


site = CustomAdminSite()

# you can register your models on this site object as usual, if needed
site.register(Model, ModelAdmin)

class PageViewAdmin(admin.ModelAdmin):
    list_display = ['hostname', 'timestamp']

admin.site.register(PageView, PageViewAdmin)
