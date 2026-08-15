<div align="center">

<h1>Problems by severity MAX</h1>

<p>
developed and maintained by
<a href="https://www.initmax.com"><img alt="initMAX" src="./.readme/logo/initmax-logo-framed.svg" height="22" valign="middle"></a>
and community
</p>

<p><strong>Zabbix's Problems by severity, without the empty cells and with a counter you can actually read.</strong><br>
Severities with no problems are left blank instead of painted, and the counter font size is yours to set - so a board on the wall stays legible from across the room.</p>

<p>
<img src="./.readme/badge/zabbix.svg" alt="Zabbix 6.0-7.4">
<img src="./.readme/badge/version.svg" alt="version 2.0.0">
<img src="./.readme/badge/php.svg" alt="PHP 7.4+">
<img src="./.readme/badge/free.svg" alt="FREE AGPLv3">
<img src="./.readme/badge/gpg.svg" alt="GPG signed">
</p>

<p>
<a href="#what-it-does"><strong>What it does</strong></a> &nbsp;·&nbsp;
<a href="#what-it-looks-like"><strong>Screenshot</strong></a> &nbsp;·&nbsp;
<a href="#install"><strong>Install</strong></a> &nbsp;·&nbsp;
<a href="#requirements"><strong>Requirements</strong></a> &nbsp;·&nbsp;
<a href="https://portal.initmax.com"><strong>Portal</strong></a> &nbsp;·&nbsp;
<a href="https://www.initmax.com/wiki/problemsbysvmax/"><strong>Docs</strong></a>
</p>

<br>

<img src="./.readme/screen/01-overview.png" width="880" alt="A Zabbix dashboard with the padding removed">

</div>

---

## What it does

Zabbix's own "Problems by severity" paints a coloured cell for every severity, whether or not anything is wrong. On a wall board that means a permanent block of colour that says nothing, and counters sized for a laptop.

**Problems by severity MAX** is that widget with two changes customers asked for:

- a severity with **no problems is not painted at all**, so colour on the board always means something,
- the **counter font size is configurable** (10-64 px) together with the **cell padding** (0-50 px), so the number is readable at the distance the screen is actually viewed from and the row height follows.

Everything else - filters, host groups, acknowledgement handling, the links through to the problem list - is Zabbix's own behaviour, unchanged.

## What you get

| Feature | Included |
| ------- | -------- |
| Empty severity cells left unpainted | Yes |
| Configurable counter font size (10-64 px) | Yes |
| Configurable cell padding (0-50 px) | Yes |
| Host groups and Totals layouts, both filters intact | Yes |
| Every Zabbix filter of the original widget (severity, tags, problem name, suppressed, acknowledged) | Yes |
| Localised into all Zabbix display languages | Yes |
| High availability ready | Yes |

There is no paid edition. Everything above is in the FREE package, under AGPLv3.

## What it looks like

<div align="center">
<img src="./.readme/screen/01-overview.png" width="880" alt="Problems by severity MAX on a dashboard">
</div>

## Install

The module ships as a **GPG-signed `deb` / `rpm` package** from the initMAX repository - `apt` / `dnf` installs it and keeps it updated.

### Easiest way - the guided installer on the Portal

Open the product page, pick your **OS**, and copy the ready-made command. It is fully public, no login needed. There's a feedback box right there too.

<p align="center"><a href="https://portal.initmax.com/catalog/zabbix-problemsbysvmax#how-to-install"><strong>→ Open the installer on the Portal</strong></a></p>

Prefer a plain archive? Every release also ships as a **ZIP** [straight from the repo](https://repo.initmax.com/zabbix/free/zip/problemsbysvmax/) - handy for offline or manual installs.

Then enable it in **Administration → General → Modules** and add the widget to a dashboard. Done.

## Requirements

|              |                                                              |
| ------------ | ------------------------------------------------------------ |
| **Zabbix**   | 6.0 · 6.2 · 6.4 · 7.0 · 7.2 · 7.4                            |
| **PHP**      | 7.4 or newer                                                 |
| **OS**       | Debian/Ubuntu · RHEL/Rocky/Alma/Oracle/Amazon · SUSE         |
| **Edition**  | FREE - there is no paid edition of this module               |
| **Languages** | Every language Zabbix supports. The two fields the widget adds carry their own catalogue; every other label is Zabbix's own string, already translated by your frontend |
| **High availability** | Ready. No server-side component and no local state - the widget reads the Zabbix database through the frontend like any other; install the package on every frontend node of an HA cluster and any node can serve it |

### One package, six Zabbix versions

Zabbix replaced its widget API in 6.4 and again in 7.0, and this widget is a fork of a core widget, so it inherits every one of those changes. The package therefore carries **two module trees** and the installer picks the right one for the frontend it finds - 6.0 and 6.2 get the legacy tree, 6.4 and newer get the modern one. Nothing to choose and nothing to configure.

The configuration dialog is deliberately the **same on all six**: same fields, same labels, same order. Two differences are the frontend's, not the widget's, and are listed here rather than papered over:

- **Widget communication is 7.0+.** Clicking a host-group row broadcasts that group to other widgets on the dashboard. Zabbix has no such mechanism before 7.0, so on 6.0-6.4 the row is simply not clickable; every count, filter and link is identical.
- **Template dashboards are 7.0+.** The "Override host" field belongs to Zabbix's template-dashboard support, which does not offer this widget type at all before 7.0. The control is left out there rather than rendered as a knob that does nothing.

## Support &amp; links

- 📚 **[Documentation / Wiki](https://www.initmax.com/wiki/problemsbysvmax/)**
- 🛒 **[Product page](https://www.initmax.com/product/problemsbysvmax/)**
- 🎫 **[Portal](https://portal.initmax.com)** - downloads, support tickets
- 💾 **Source code** (AGPLv3) - included in every package and published as a [source archive](https://repo.initmax.com/zabbix/free/zip/problemsbysvmax/) on repo.initmax.com
- ✉️ **[support@initmax.com](mailto:support@initmax.com)**

---

<div align="center">
<sub><a href="https://www.gnu.org/licenses/agpl-3.0.html">AGPLv3</a> &nbsp;·&nbsp; © 2021-2026 initMAX s.r.o.</sub>
</div>
