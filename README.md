# IFOAM Newsletter API

## Endpoint

/newsletter-api

POST the following JSON object to register a new subscription:

```
{
  "first_name": "SOME FIRST NAME",
  "last_name": "SOME LAST NAME",
  "email": "SOME EMAIL",
  "organization": "SOME ORGANIZATION NAME",
  "job_title": "SOME JOB TITLE",
  "receive_newsletter": TRUE | FALSE,
  "receive_press_releases": TRUE | FALSE,
  "api_key": "YOUR SECRET API KEY"
}
```

## Returns

A JSON object

```
{
  "status": "success" | "error",
  "description": "SOME DESCRIPTION"
}
```