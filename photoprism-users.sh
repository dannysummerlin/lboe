#!/bin/bash
while getopts e:p:c: flag
do
	case "${flag}" in
		e) email=${OPTARG};;
		p) pass=${OPTARG};;
		c) command=${OPTARG};;
	esac
done

case "${command}" in
	add) docker compose exec photoprism photoprism users add -p "${pass}" -m "${email}" "${email}";;
	rm)  docker compose exec photoprism photoprism users rm "${email}";;
	reset)  docker compose exec photoprism photoprism users mod --password "${pass}" "${email}";;
	help) cat << EOF

Use this to manage PhotoPrism users:
 - Add User: photoprism-users.sh -c add -e "EMAIL" -p "PASSWORD"
 - Reset Password: photoprism-users.sh -c reset -e "EMAIL" -p "PASSWORD"
 - Remove User: photoprism-users.sh -c rm -e "EMAIL"

EOF
esac
```
