#!/usr/bin/env -S uv run --script
# /// script
# requires-python = ">=3.13"
# dependencies = [
#  "dotenv",
#  "requests",
#  "office365-rest-python-client",
# ]
# ///

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

int_versions_to_keep = int(VERSIONS_TO_KEEP)

def print_progress(items):
    time.sleep(0.02)
    print("Items read: {0}".format(len(items)))

def get_file_versions(url, page_size = 5000, list_title = "Documents"):
    context = ClientContext(url).with_credentials( UserCredential(USERNAME, PASSWORD) )
    library = context.web.default_document_library()
    items = (
        library.items
        .select(['Id','FileSystemObjectType','versions/ID','versions/versionlabel','versions/Length','versions/iscurrentversion','versions/created'])
        .expand(["File","File/versions"])
        .get_all(page_size, print_progress).execute_query()
    )
    items = [i for i in items if i.file_system_object_type == 0]
    return items, context


def cleanup_site(site_name: str, folders_to_skip = [], confirm_delete=False, save_output=False, only_before=dt.date.today()):
    output = []
    items = []
    impact = []
    print("Fetching items...")
    url = f'https://{SHAREPOINT_PREFIX}.sharepoint.com/sites/{site_name}'
    items, context = get_file_versions(url)

    if folders_to_skip:
        print("Filtering items...")
        folder_pattern = re.compile('|'.join(map(re.escape, folders_to_skip)))
        items = [i for i in items if not bool(folder_pattern.search(i.file.serverRelativeUrl))]
        print(f"Items after skipped folders: {len(items)}")

    error_count = 0
    for item in items:
        if True or len(item.file.versions) > int_versions_to_keep:
            remove_versions = [i for i in item.file.versions if not i.is_current_version and float(i.version_label) < ( item.file.major_version - int_versions_to_keep) and i.created.date() < only_before]
            try:
                for i in remove_versions:
                    impact.append({
                        'size in kb': int(i.get_property('Length')) / 1000,
                        'path': item.file.serverRelativeUrl,
                        'created_date': i.created.date(),
                        'version': i.version_label,
                    })
                    if confirm_delete:
                        item.file.versions.delete_by_label(i.version_label).execute_query()
                if save_output:
                    output.append({
                        'name': item.file.name,
                        'path': item.file.serverRelativeUrl,
                        'size in kb': item.file.length/1000,
                        'versions': len(item.file.versions),
                    })
            except:
                error_count = error_count + 1
                time.sleep(0.1)
                continue

    print(f"error count: {error_count}")
    if impact:
        with open(f"sharepoint_impacted_versions_{site_name}.csv", 'w', newline='', encoding="utf-8") as csvfile:
            fieldnames = impact[0].keys()
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames, dialect='excel')
            writer.writeheader()
            writer.writerows(impact)
    if save_output and output:
        with open(f"sharepoint_items_cleaned_{site_name}.csv", 'w', newline='', encoding="utf-8") as csvfile:
            fieldnames = output[0].keys()
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames, dialect='excel')
            writer.writeheader()
            writer.writerows(output)
    return output

site_name = "Archive"
# Report only:
cleanup_site(site_name=site_name, only_before=dt.date(2025,1,1), folders_to_skip=['Important'], save_output=True)
# CAUTION, will delete:
# cleanup_site(site_name=site_name, only_before=dt.date(2025,1,1), folders_to_skip=['Important'], confirm_delete=True)
