# Nano Analytics

Simple self-hosted web visitors counter.

- It tries to count how many unique users accessed different pages on a website,
  without looking at or storing any personal information.
- Only one random UUID cookie is used.
- One user using multiple web browsers or devices cannot be tracked and will be
  counted as multiple users.
- The cookie expires after 30 days and next access will be counted as new
  user.
- The counter has no way to prevent fake entries to be added by bad actors,
  but should be otherwise secure.
- Access to the statistics is not restricted.

## Setup
Requirements: webserver with PHP and MariaDB/MySQL.

1. Change DB settings in nano-analytics.php and save it to root of your website.
2. Create database using setup.sql
3. Include nano-analytics.js in all pages where you want to collect statistics.
4. Open /nano-analytics.php to see the statistics

## Legal
Disclaimer: I'm not a legal expert, this is just my opinion, I'm not liable for
any claim, damages or other liability.

Since the counter doesn't collect any personal information and uses only one
random cookie that is required for it to function, consent from users should not
be needed and this use should fit into legitimate interests lawful basis.
In that case all you need is to inform the user. Here is an example text:

```
This page informs you of our policies regarding Personal Information and cookies.
We use the following information only in anonymous statistics used for improving
the Site and detecting abuse (legitimate interest). By using the Site, you agree
to the collection and use of information in accordance with this policy.

Cookies:
Cookies are files with a small amount of data. Cookies are sent to your browser
from a website and stored on your computer. In our case, these include an
anonymous unique identifier and are automatically removed after 30 days. You can
nstruct your browser to refuse all cookies or to indicate when a cookie is being
sent.

Log Data:
The only information we log is the pages of our Site that you visit, the time
and date of your visit and random ID.

Security:
We do not collect any personal information about you, your browser, or device.
We do not use any third-party services to collect information or track users.
Anonymous statistics generated by our Site are publicly available.

Changes to this Policy:
This policy is effective as of [date] and will remain in effect except with
respect to any changes in its provisions in the future, which will be in effect
immediately after being posted on this page. We reserve the right to update or
change this policy at any time. Your continued use of the Service after we post
any modifications to this policy on this page will constitute your
acknowledgment of the modifications and your consent.
```