#!/usr/bin/env -S uv run --script
# /// script
# requires-python = ">=3.13"
# dependencies = [
#  "dotenv",
#  "requests",
#  "office365-rest-python-client",
# ]
# ///

import os
import re
import csv
import time
import dotenv
import datetime as dt
from pprint import pprint
from office365.sharepoint.client_context import ClientContext, UserCredential

# .env values
# USERNAME=
# PASSWORD=
# SHAREPOINT_PREFIX=
# VERSIONS_TO_KEEP=
for k in dotenv.dotenv_values():
	globals()[k] = dotenv.dotenv_values()[k]

def print_progress(items):
	print("Items read: {0}".format(len(items)))

def get_site_items(site_name, page_size = 5000, list_title = "Documents", list_versions=True, list_all_files=True, list_recycle_bin=True):
	user_credentials = UserCredential(USERNAME,PASSWORD)
	url = f'https://{SHAREPOINT_PREFIX}.sharepoint.com/sites/{site_name}'
	ctx = ClientContext(f'{url}').with_credentials(user_credentials)
	doc_lib = ctx.web.default_document_library()
	results = []
	file_log = []
	if list_versions:
		results = []
		items_with_versions = (
			doc_lib.items
			.select(['Id','FileSystemObjectType','versions/ID','versions/versionlabel','versions/Length','versions/iscurrentversion','versions/created'])
			.expand(["File","File/versions"])
			.get_all(page_size, print_progress).execute_query()
		)
		for item in items_with_versions:
			try:
				if item.file_system_object_type == 0: # file
					name = item.file.name
					url = item.file.serverRelativeUrl
					path = item.file.serverRelativeUrl.split('/')
					level1 = path[4] if len(path) >= 5 else None
					level2 = path[5] if len(path) >= 6 else None
					level3 = path[6] if len(path) >= 7 else None
					level4 = path[7] if len(path) >= 8 else None
					level5 = path[8] if len(path) >= 9 else None
					level6 = path[9] if len(path) >= 10 else None
					level7 = path[10] if len(path) >= 11 else None
					level8 = path[11] if len(path) >= 12 else None
					level9 = path[12] if len(path) >= 13 else None
					level10 = path[13] if len(path) >= 14 else None
					level11 = path[14] if len(path) >= 15 else None
					level12 = path[15] if len(path) >= 16 else None
					level13 = path[16] if len(path) >= 17 else None
					level14 = path[17] if len(path) >= 18 else None
					level15 = path[18] if len(path) >= 19 else None
					last_modified = str(item.file.time_last_modified)
					if item.file.versions:
						for i in item.file.versions:
							results.append({
								"site": site_name,
								'version': i.version_label,
								'size in kb': int(i.get_property('Length')) / 1000,
								"name": name,
								'created_date': i.created.date(),
								"last_modified": last_modified,
								"url": url,
								"note": "",
								"level1": level1, "level2": level2, "level3": level3, "level4": level4, "level5": level5, "level6": level6, "level7": level7, "level8": level8, "level9": level9, "level10": level10, "level11": level11, "level12": level12, "level13": level13, "level14": level14, "level15": level15,
							})
					else:
						results.append({
							"site": site_name,
							'version': "1.0",
							'size in kb': (item.file.length/1000),
							"name": name,
							'created_date': "",
							"last_modified": last_modified,
							"url": url,
							"note": "",
							"level1": level1, "level2": level2, "level3": level3, "level4": level4, "level5": level5, "level6": level6, "level7": level7, "level8": level8, "level9": level9, "level10": level10, "level11": level11, "level12": level12, "level13": level13, "level14": level14, "level15": level15,
						})
					file_log.append(item.id)
			except:
				print(f'skipping record {idx}')
				pprint(item)

	# to catch any files that do not return from versions query
	if list_all_files:
		items_wo_versions = (
			doc_lib.items
			.select(['Id','FileSystemObjectType'])
			.get_all(page_size, print_progress).execute_query()
		)
		for item in items_wo_versions:
			if item.file_system_object_type != 1 and item.id not in file_log:
				name = item.file.name
				url = item.file.serverRelativeUrl
				last_modified = str(item.file.time_last_modified)
				results.append({
					"site": site_name,
					'version': "1.0",
					'size in kb': (item.file.length/1000),
					"name": name,
					'created_date': "",
					"last_modified": last_modified,
					"url": url,
					"note": "",
				})

	# get recycle bin(s) from site
	if list_recycle_bin:
		# dict_keys(['AuthorEmail', 'AuthorName', 'DeletedByEmail', 'DeletedByName', 'DeletedDate', 'DeletedDateLocalFormatted', 'DirName', 'DirNamePath', 'Id', 'ItemState', 'ItemType', 'LeafName', 'LeafNamePath', 'Size', 'Title'])
		recycle_bin_items = ctx.site.recycle_bin.get().execute_query()
		for item in recycle_bin_items:
			name = item.title
			url = f"{item.leaf_name_path}/{item.leaf_name}"
			last_modified = str(item.deleted_date)
			results.append({
				"site": site_name,
				'version': "1.0",
				'size in kb': int(item.size)/1000,
				"name": name,
				'created_date': "",
				"last_modified": last_modified,
				"url": url,
				"note": f"{item.item_type} | {item.item_state}",
			})
		recycle_bin_items = ctx.web.recycle_bin.get_all(page_size, print_progress).execute_query()
		for item in recycle_bin_items:
			name = item.title
			url = f"{item.leaf_name_path}/{item.leaf_name}"
			last_modified = str(item.deleted_date)
			results.append({
				"site": site_name,
				'version': "1.0",
				'size in kb': int(item.size)/1000,
				"name": name,
				'created_date': "",
				"last_modified": last_modified,
				"url": url,
				"note": f"{item.item_type} | {item.item_state}",
			})
	with open(f"{site_name}_storage_report.csv", "w", newline="", encoding="utf-8") as f:
		dict_writer = csv.DictWriter(f, results[0].keys())
		dict_writer.writeheader()
		dict_writer.writerows(results)

for site in ["sitename"]:
	f = get_site_items(site)
