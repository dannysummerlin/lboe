# uv pip install Office365-REST-Python-Client --system
import os
import csv
from office365.sharepoint.client_context import ClientContext, UserCredential

def print_progress(items):
	print("Items read: {0}".format(len(items)))

def get_file_versions(site_name, page_size = 5000, list_title = "Documents"):
	app_username = os.environ['SHAREPOINT_USER']
	app_password = os.environ['SHAREPOINT_PASSWORD']
	user_credentials = UserCredential(app_username,app_password)
	base_url = os.environ['SHAREPOINT_BASEURL']
	context = ClientContext(f'{base_url}{site_name}').with_credentials(user_credentials)
	library = context.web.default_document_library()
	items = (
		library.items
		.filter("ContentType ne 'Folder'")
		.select(['versions/versionlabel','versions/iscurrentversion'])
		.expand(["File","versions"])
		.get_all(page_size, print_progress).execute_query()
	)
	return items, context

def clean_site_items(site_name, page_size = 5000, list_title = "Documents", keep_at_least = 2,confirmation = False):
	try:
		items, context = get_file_versions(site_name, page_size, list_title)
		if confirmation:
			for idx, item in enumerate(items):
				if item.file.major_version > keep_at_least:
					[i.delete_object().execute_query() for i in item.versions if not i.is_current_version and float(i.version_label) < len(versions) - keep_at_least]
			return True
		else:
			raise('skipping unconfirmed job')
	except Exception as e:
		print(e)
		return False
