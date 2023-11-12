from django.contrib import admin
from django.conf.urls import url
from django.template.response import TemplateResponse
from security.models import Security
from .models import PageView

# Register your models here.

@admin.register(Security)
class SecurityAdmin(admin.ModelAdmin):

    def get_urls(self):

        # get the default urls
        urls = super(SecurityAdmin, self).get_urls()

        # define security urls
        security_urls = [
            url(r'^configuration/$', self.admin_site.admin_view(self.security_configuration))
            # Add here more urls if you want following same logic
        ]

        # Make sure here you place your added urls first than the admin default urls
        return security_urls + urls

    # Your view definition fn
    def security_configuration(self, request):
        context = dict(
            self.admin_site.each_context(request), # Include common variables for rendering the admin template.
            something="test",
        )
        return TemplateResponse(request, "configuration.html", context)

class PageViewAdmin(admin.ModelAdmin):
    list_display = ['hostname', 'timestamp']

admin.site.register(PageView, PageViewAdmin)
