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
	sharepoint_prefix = os.environ['SHAREPOINT_PREFIX']
	url = f'https://{sharepoint_prefix}.sharepoint.com/sites/{site_name}'
	context = ClientContext(url).with_credentials(user_credentials)
	library = context.web.default_document_library()
	items = (
		library.items
		.select(['Id','FileSystemObjectType','versions/ID','versions/versionlabel','versions/iscurrentversion'])
		.expand(["File","File/versions"])
		.get_all(page_size, print_progress).execute_query()
	)
	items = [i for i in items if i.file_system_object_type == 0]
	return items, context

def clean_site_items(site_name, page_size = 5000, list_title = "Documents", keep_at_least = 2,confirmation = False):
	try:
		output = []
		items, context = get_file_versions(site_name)
		for item in items:
			if len(item.file.versions) > versions_to_keep:
				output.append({
					'name': item.file.name,
					'size': item.file.length/1000,
					'versions': len(item.file.versions),
				})
				[item.file.versions.delete_by_label(i.version_label).execute_query() for i in item.file.versions if not i.is_current_version and float(i.version_label) < ( item.file.major_version - versions_to_keep )]
			return output
		else:
			raise('skipping unconfirmed job')
	except Exception as e:
		print(e)
		return False
