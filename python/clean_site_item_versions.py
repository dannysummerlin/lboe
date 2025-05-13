# uv pip install Office365-REST-Python-Client --system
import os
import csv
from office365.sharepoint.client_context import ClientContext, UserCredential

def print_progress(items):
	print("Items read: {0}".format(len(items)))

def clean_site_items(site_name, page_size = 5000, list_title = "Documents"):
	app_username = os.environ['SHAREPOINT_USER']
	app_password = os.environ['SHAREPOINT_PASSWORD']
	user_credentials = UserCredential(app_username,app_password)
	base_url = os.environ['SHAREPOINT_BASEURL']
	ctx = ClientContext(f'{base_url}{site_name}').with_credentials(user_credentials)
	doc_lib = ctx.web.default_document_library()
	items = (
		doc_lib.items.select(["FileSystemObjectType"]).expand(["File"])
		.get_all(page_size, print_progress).execute_query()
	)
	for idx, item in enumerate(items):
		if item.file_system_object_type == 0:
			if item.file.major_version > 2:
				versions = item.file.versions.top(100).get().execute_query()
				[i.delete_object().execute_query() for i in versions if float(i.version_label) < len(versions) - 2]
	return False
