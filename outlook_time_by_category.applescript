-- ============================================================
-- Outlook Weekly Time-by-Category Reporter
-- Works with Microsoft Outlook (Legacy) on macOS
-- Run via Script Editor or: osascript outlook_time_by_category.applescript
-- ============================================================

on minutesBetween(startDate, endDate)
	return (endDate - startDate) / 60
end minutesBetween

on formatDuration(totalMinutes)
	set hrs to totalMinutes div 60
	set mins to totalMinutes mod 60
	if hrs > 0 and mins > 0 then
		return (hrs as string) & "h " & (mins as string) & "m"
	else if hrs > 0 then
		return (hrs as string) & "h"
	else
		return (mins as string) & "m"
	end if
end formatDuration

on indexOfItem(theList, theItem)
	repeat with i from 1 to count of theList
		if item i of theList is theItem then return i
	end repeat
	return 0
end indexOfItem

on padRight(str, targetLen)
	set s to str as string
	repeat while (count of s) < targetLen
		set s to s & " "
	end repeat
	return s
end padRight

-- ---- MAIN ----

set today to current date
set daysSinceMonday to (weekday of today as integer) - 2
-- display dialog (weekday of today as integer)
if daysSinceMonday < 0 then set daysSinceMonday to daysSinceMonday + 7

set weekStart to today - (daysSinceMonday * days)
set weekStart to weekStart - (time of weekStart)
set weekEnd to weekStart + 7 * days

set weekStartLabel to short date string of weekStart
set weekEndLabel to short date string of (weekEnd - 1 * days)

set categoryNames to {}
set categoryMinutes to {}
set totalEvents to 0
set skippedEvents to 0
set targetCalendarName to "Calendar"

-- ---- Phase 1: verify calendar exists ----
tell application "Microsoft Outlook"
	set matchedCals to (every calendar whose name is targetCalendarName)
	if (count of matchedCals) = 0 then
		display dialog "No calendar named \"" & targetCalendarName & "\" found in Microsoft Outlook." buttons {"OK"} default button "OK"
		return
	end if
end tell

set weekEvents to {}
tell application "Microsoft Outlook"
	set calRef to calendar 4
	set weekEvents to (every calendar event of calRef whose start time >= weekStart and start time < weekEnd)
	set weekOccurrences to (every calendar event occurrence of calRef whose start time >= weekStart and start time < weekEnd)
end tell

set eventCount to count of weekEvents

set evStarts to {}
set evEnds to {}
set evCategories to {}

tell application "Microsoft Outlook"
	repeat with evIdx from 1 to eventCount
		set anEvent to item evIdx of weekEvents
		set end of evStarts to start time of anEvent
		set end of evEnds to end time of anEvent
		
		set evCategory to "(Uncategorized)"
		try
			set catObj to category of anEvent
			if class of catObj is list then
				if (count of catObj) > 0 then
					set evCategory to name of (item 1 of catObj)
				end if
			else
				set evCategory to name of catObj
			end if
		end try
		try
			set evCategory to evCategory as string
		on error
			set evCategory to "(Uncategorized)"
		end try
		if evCategory is "" then set evCategory to "(Uncategorized)"
		set end of evCategories to evCategory
	end repeat
end tell

-- Now process the collected data â no tell block here so my/handler calls are fine
repeat with evIdx from 1 to eventCount
	set evStart to item evIdx of evStarts
	set evEnd to item evIdx of evEnds
	set evCategory to item evIdx of evCategories
	
	if evStart is missing value or evEnd is missing value then
		set skippedEvents to skippedEvents + 1
	else if evStart >= weekEnd or evEnd =< weekStart then
		-- not this week
	else
		set clampedStart to evStart
		set clampedEnd to evEnd
		if clampedStart < weekStart then set clampedStart to weekStart
		if clampedEnd > weekEnd then set clampedEnd to weekEnd
		
		set dur to my minutesBetween(clampedStart, clampedEnd)
		
		if dur <= 0 or dur >= 1440 then
			set skippedEvents to skippedEvents + 1
		else
			set totalEvents to totalEvents + 1
			
			set idx to my indexOfItem(categoryNames, evCategory)
			if idx is 0 then
				set end of categoryNames to evCategory
				set end of categoryMinutes to dur
			else
				set item idx of categoryMinutes to (item idx of categoryMinutes) + dur
			end if
		end if
	end if
end repeat

-- ---- Sort by duration descending ----
set n to count of categoryNames
repeat with i from 1 to n - 1
	repeat with j from 1 to n - i
		if item j of categoryMinutes < item (j + 1) of categoryMinutes then
			set tmpM to item j of categoryMinutes
			set item j of categoryMinutes to item (j + 1) of categoryMinutes
			set item (j + 1) of categoryMinutes to tmpM
			set tmpN to item j of categoryNames
			set item j of categoryNames to item (j + 1) of categoryNames
			set item (j + 1) of categoryNames to tmpN
		end if
	end repeat
end repeat

set totalMinutesAll to 0
repeat with m in categoryMinutes
	set totalMinutesAll to totalMinutesAll + m
end repeat

-- ---- Build report ----
set sep to "-------------------------------------------------------" & return
set report to "=======================================================" & return
set report to report & "  OUTLOOK WEEKLY TIME REPORT" & return
set report to report & "  Week: " & weekStartLabel & " to " & weekEndLabel & return
set report to report & "=======================================================" & return & return
set report to report & my padRight("CATEGORY", 30) & my padRight("DURATION", 12) & "% OF TOTAL" & return
set report to report & sep

repeat with i from 1 to count of categoryNames
	set catMins to item i of categoryMinutes
	set pctStr to "â"
	if totalMinutesAll > 0 then
		set pctStr to (round ((catMins * 100) / totalMinutesAll)) & "%"
	end if
	set report to report & my padRight(item i of categoryNames, 30) & my padRight(my formatDuration(catMins), 12) & pctStr & return
end repeat

set report to report & sep
set report to report & my padRight("TOTAL", 30) & my padRight(my formatDuration(totalMinutesAll), 12) & "100%" & return & return
set report to report & "Events processed : " & eventCount & " (year to date)" & return
set report to report & "This-week counted: " & totalEvents & return
set report to report & "This-week skipped: " & skippedEvents & " (all-day or zero-duration)" & return
set report to report & "=======================================================" & return

display dialog report buttons {"Copy to Clipboard", "OK"} default button "OK" with title "Weekly Time Report" giving up after 120
if button returned of result is "Copy to Clipboard" then
	set the clipboard to report
end if
