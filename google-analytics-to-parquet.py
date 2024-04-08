# install oauth2client and google-api-python-client
import re
import sys
import csv
import glob
import argparse
import pyarrow as pa
import pyarrow.parquet as pq
from datetime import datetime, timedelta
from apiclient.discovery import build
from oauth2client.service_account import ServiceAccountCredentials

def queryGoogleAnalytics(
		viewId=None,
		startDate='2daysAgo',
		endDate='today',
		dimensions='ga:city',
		metrics='ga:sessions',
		startIndex=1,
		maxResults=10000,
		data={'rows':[]},
		debug=False,
		tryCount=0,
		service=None,
		extraName=""
	):
	if not viewId:
		print('You must specify a View ID')
		exit()
	tryLimit = 3
	try:
		if not service:
			credentials = ServiceAccountCredentials.from_json_keyfile_name('client_secrets.json', scopes=['https://www.googleapis.com/auth/analytics.readonly'])
			service = build('analytics', 'v3', credentials=credentials)
		if startIndex == 1:
			print(f'Retrieving view {viewId} from {startDate} to {endDate} {extraName}', end='', flush=True)
			data={'rows':[]}
		else:
			print(f'.', end='', flush=True)
		results = service.data().ga().get(
			ids=f'ga:{viewId}',
			start_date=startDate,
			end_date=endDate,
			metrics=metrics,
			dimensions=dimensions,
	        start_index=int(startIndex),
	        max_results=int(maxResults),
		).execute()
		if 'headers' not in data:
			data['headers'] = results['columnHeaders']
		if(results['totalResults'] != 0):
			data['rows'] += results['rows']
			if 'nextLink' in results:
				startIndexSearch = re.search(r'start-index=(\d+)', results['nextLink'])
				startIndex = startIndexSearch.group(1)
				return queryGoogleAnalytics(
					viewId=viewId,
					startDate=startDate,
					endDate=endDate,
					metrics=metrics,
					dimensions=dimensions,
					startIndex=startIndex,
					maxResults=maxResults,
					data=data,
					debug=debug,
					tryCount=tryCount,
					service=service,
					extraName=extraName
				)
			# else:
		return data
	except:
		print(vars(sys.exc_info()[0]))
		if tryCount < tryLimit:
			tryCount +=1
			print(f'Error retrieving data, retry #{tryCount}')
			return queryGoogleAnalytics(
				viewId=viewId,
				startDate=startDate,
				endDate=endDate,
				metrics=metrics,
				dimensions=dimensions,
				startIndex=startIndex,
				maxResults=maxResults,
				data=data,
				debug=debug,
				tryCount=tryCount,
				service=service,
				extraName=extraName
			)
		else:
			print(f'Too many errors retrieving data, exiting')

def processAnalytics(targetDir = None, mode = 'parquet', viewId=None, startDate='2daysAgo', endDate='today',
		metrics='ga:sessions', dimensions='ga:city', startIndex=1, maxResults=10000, debug=False,extraName=""):
	if not viewId or not targetDir:
		print('You must provide a view Id and target directory')
		exit()
	results = queryGoogleAnalytics(viewId=viewId, startDate=startDate, endDate=endDate,
				metrics=metrics, dimensions=dimensions, startIndex=startIndex, maxResults=maxResults, debug=debug, extraName=extraName)
	fileName = f'{viewId}-{startDate}-{endDate}{extraName}'
	if(results['rows']):
		match mode:
			case 'csv':
				with open(f"{targetDir}\\{fileName}.csv", 'w', newline='') as file:
					writer = csv.DictWriter(file, fieldnames=results['headers'])
					writer.writeheader()
					for _ in results['rows']:
						writer.writerow(_)
			case 'parquet':
				fromHeaders = []
				for h in results['headers']:
					fromHeaders.append(h['name'])
				gaData = list(zip(*results['rows'][::-1]))
				pq.write_table(pa.Table.from_arrays(gaData, names=fromHeaders), f"{targetDir}\\{fileName}.parquet")
		print(f'\nSaved results in {fileName}.{mode}')
	else:
		print(f'\nNo results for {fileName} {mode}')

################################
# Command line utility features
################################
# parser = argparse.ArgumentParser()
# parser.add_argument('--view', '-v', help='The ID of the view you want to export', default=None, type=str)
# parser.add_argument('--dimensions', '-d', help='Comma seperated list of GA dimensions', default='ga:country,ga:region,ga:hostname,ga:pagePath,ga:deviceCategory,ga:dateHourMinute,ga:sourceMedium', type=str)
# parser.add_argument('--metrics', '-m', help='Comma seperated list of GA metrics', default='ga:avgTimeOnPage,ga:entrances,ga:exits,ga:pageviews,ga:uniquePageviews,ga:bounceRate,ga:sessions,ga:newUsers,ga:users', type=str)
# parser.add_argument('--targetDir', '-t', help='Folder to output files to', default='\\Data Sources\\Google Analytics\\', type=str)
# # left over because can only make limited number of met/dim requests at a time
# 	# metrics='ga:pagesPerSession,',
# 	#dimensions='ga:pageDepth,ga:channelGrouping',
# parser.add_argument('--startDate', '-s', help='Start date', default='2022-01-01', type=str)
# parser.add_argument('--endDate', '-e', help='End date', default='2022-12-31', type=str)
# args = parser.parse_args()

################################
# For manual execution
################################
fields = [
{
    "name": "test",
    "dimensions":"ga:dateHour",
    "metrics":"ga:sessions"
},
{
    "name": "sessionDate",
    "dimensions":"ga:dateHour,ga:source,ga:medium",
    "metrics":"ga:sessions"
},
{
    "name": "pageTitle",
    "dimensions":"ga:pagePath,ga:pageTitle",
    "metrics":"ga:sessions"
},
{
    "name": "platform",
    "dimensions":"ga:pagePath,ga:dateHour,ga:medium,ga:source,ga:browser",
    "metrics":"ga:sessions"
},
{
    "name": "duration",
    "dimensions":"ga:dateHour,ga:pagePath",
    "metrics":"ga:pageviews,ga:sessionDuration"
},
{
    "name": "geography",
    "dimensions":"ga:pagePath,ga:dateHour,ga:region,ga:city,ga:country",
    "metrics":"ga:sessions"
},
{
    "name": "audience",
    "dimensions":"ga:pagePath,ga:date,ga:userGender,ga:userAgeBracket,ga:language",
    "metrics":"ga:avgSessionDuration,ga:sessionDuration,ga:sessions"
}]

###########################
# Reference execution
###########################
if False: '''
VIEW_ID = 11111
for y in range(22,17,-1):
	for f in ga.fields:
		try:
			ga.processAnalytics(startDate=f'20{y}-01-01', endDate=f'20{y}-12-31',
				maxResults=10000,
				viewId=VIEW_ID,
				dimensions=f["dimensions"],
				metrics=f["metrics"],
				targetDir=".",
				extraName=f["name"]
			)
		except Exception as e:
			print(e)
'''
