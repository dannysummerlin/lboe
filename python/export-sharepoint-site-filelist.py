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
	return {items, context}

def get_site_items(site_name, page_size = 5000, list_title = "Documents", with_path=False, save_csv=True):
	results = []
	items, context = get_file_versions(site_name, page_size, list_title)
	if save_csv:
		for idx, item in enumerate(items):
			try:
				path = item.file.serverRelativeUrl.split('/')
				record = {
					"url": item.file.serverRelativeUrl,
					"name": item.file.name,
					"last_modified": str(item.file.time_last_modified),
					"size": (item.file.length/1000),
					"version": item.file.major_version,
					"site": path[2] if len(path) >= 3 else None,
				}
				if with_path:
					record["level1"] = path[4] if len(path) >= 5 else None
					record["level2"] = path[5] if len(path) >= 6 else None
					record["level3"] = path[6] if len(path) >= 7 else None
					record["level4"] = path[7] if len(path) >= 8 else None
					record["level5"] = path[8] if len(path) >= 9 else None
					record["level6"] = path[9] if len(path) >= 10 else None
					record["level7"] = path[10] if len(path) >= 11 else None
					record["level8"] = path[11] if len(path) >= 12 else None
					record["level9"] = path[12] if len(path) >= 13 else None
					record["level10"] = path[13] if len(path) >= 14 else None
					record["level11"] = path[14] if len(path) >= 15 else None
					record["level12"] = path[15] if len(path) >= 16 else None
					record["level13"] = path[16] if len(path) >= 17 else None
					record["level14"] = path[17] if len(path) >= 18 else None
					record["level15"] = path[18] if len(path) >= 19 else None
				results.append(record)
			except Exception as e:
				print(item.file.name, e)
				print('skipping record')
		with open(f"{site_name}.csv", "w", newline="", encoding="utf-8") as f:
			dict_writer = csv.DictWriter(f, results[0].keys())
			dict_writer.writeheader()
			dict_writer.writerows(results)
		return f"{site_name}.csv"
	else:
		return items
