// general function to send Google Forms output to a webhook
function callWebhook(url, secret, actionName, fieldMap) {
	const allResponses = FormApp.getActiveForm().getResponses()
	const latestResponse = allResponses[allResponses.length - 1]
	const responses = latestResponse.getItemResponses()

	let payload = {"actionName": actionName}
	responses.forEach(r => payload[ fieldMap[ r.getItem().getTitle() ] ] = r.getResponse() ?? "")
	const options = {
		"method": "post",
		"headers": {"x-custom-secret": secret},
		"contentType": "application/json",
		"payload": JSON.stringify(payload),
		"muteHttpExceptions": true
	}
	const response = UrlFetchApp.fetch(url, options)
}

// ----------------------------------
// per form calls
function sample_submitCreateUser(e) {
  const url = "https://webhook.site/a2cec67c-e069-4bb4-8006-fb1ce6088d1b"
  const secret = "Not-Real"
  const actionName = "newUser"
  const fieldMap = {
    "First Name": "firstName",
    "Last Name": "lastName",
    "Account Type": "employeeType",
    "Department": "department",
    "Title": "title",
    "Recovery Email": "recoveryEmail"
  }
  callWebhook(url, secret, actionName, fieldMap)
}

