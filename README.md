# 📄 JATS Engine

### Convert DOCX Manuscripts into JATS XML for Scholarly Publishing

**A PHP library that parses Microsoft Word `.docx` manuscripts and builds structured JATS (Journal Article Tag Suite) XML ready for scholarly publishing platforms and indexing services.**

---

<p align="center">
  <img src="https://img.shields.io/badge/PHP-^8.1-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/JATS-1.0%20%7C%201.1%20%7C%201.2%20%7C%201.3-green?style=for-the-badge&logo=xml&logoColor=white" alt="JATS Version">
  <img src="https://img.shields.io/badge/license-GPL%20v3.0-blue?style=for-the-badge" alt="License">
  <img src="https://img.shields.io/badge/build-passing-brightgreen?style=for-the-badge&logo=github-actions&logoColor=white" alt="Build">
  <img src="https://img.shields.io/badge/release-v1.0.0--alpha-lightgrey?style=for-the-badge" alt="Release">
</p>

<br>

<p align="center">
  <em>📝 DOCX → 🔍 Parse → 🧱 Build → 📄 JATS XML</em>
</p>

---

## 📖 About

**JATS Engine** is the conversion core behind the **Wizdam** publishing ecosystem. It takes a Microsoft Word `.docx` manuscript and automatically builds a fully structured **JATS XML** document — the industry standard format for journal article interchange (ANSI/NISO Z39.96).

The engine is designed specifically to integrate with **Open Journal Systems (OJS) 2.x** via its native DAO layer, but its modular architecture allows it to be adapted for any PHP‑based publishing workflow.

---

## ✨ What It Does

| 🧱 Builder | 📋 Responsibility |
| :--- | :--- |
| `MetadataBuilder` | Reads article, author, journal, and issue data from OJS 2.x DAOs and builds the JATS `<front>` element — including journal meta, article meta, publication history, and citation list. |
| `BodyBuilder` | Opens the `.docx` archive, parses the WordprocessingML body, and builds the JATS `<body>` with full section hierarchy, tables, figures, math, and inline formatting. |

| 🔍 Parser | 📋 Responsibility |
| :--- | :--- |
| `TextParser` | Detects heading levels via Word outline styles, parses paragraph content recursively (deep-diving through textboxes, shapes, and alternate content wrappers), and preserves bold/italic/underline formatting. |
| `TableParser` | Converts Word tables into JATS `<table-wrap>` elements — including header detection, colspan/rowspan merging, and structured `<thead>`/`<tbody>` output. |
| `MathParser` | Transforms Office Math Markup Language (OMML) into MathML using XSLT, then wraps it as JATS `<inline-formula>` or `<disp-formula>`. |
| `ImageHandler` | Extracts images from the `.docx` zip, converts legacy EMF/WMF metafiles to PNG via PHP Imagick, and generates JATS `<graphic>` references. |

---

## 🚀 Quick Start

### Prerequisites

| Software | Version |
| :--- | :--- |
| **PHP** | ≥ 8.1 |
| **PHP Extensions** | `zip`, `xsl`, `dom`, `imagick` (optional, for EMF/WMF conversion) |
| **OJS** | 2.4.x (for native DAO integration) |

### Installation

```bash
composer require wizdam/jats-engine
```

### Usage

```php
use Wizdam\JatsEngine\Builders\MetadataBuilder;
use Wizdam\JatsEngine\Builders\BodyBuilder;

$articleId = 123;
$docxPath  = '/path/to/manuscript.docx';

// 1. Create DOM document with JATS root
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->createElement('article');
$root->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
$root->setAttribute('dtd-version', '1.1');
$dom->appendChild($root);

// 2. Build front matter from OJS database
$metadataBuilder = new MetadataBuilder($articleId);
$metadataBuilder->buildFront($dom);

// 3. Build body from DOCX
$bodyBuilder = new BodyBuilder();
$bodyBuilder->setArticleId($articleId);
$bodyBuilder->setDocxPath($docxPath);
$bodyBuilder->buildBody($dom);

// 4. Output JATS XML
echo $dom->saveXML();
```

---

## 🧪 Example Output

```xml
<?xml version="1.0" encoding="UTF-8"?>
<article xmlns:xlink="http://www.w3.org/1999/xlink" dtd-version="1.1">
  <front>
    <journal-meta>
      <journal-title-group>
        <journal-title>Journal of Applied Sciences</journal-title>
      </journal-title-group>
      <issn publication-format="print">1234-5678</issn>
    </journal-meta>
    <article-meta>
      <title-group>
        <article-title>Solar Panel Adoption in Rural Java</article-title>
      </title-group>
      <contrib-group>...</contrib-group>
      <pub-date date-type="pub">
        <year>2026</year>
      </pub-date>
    </article-meta>
  </front>
  <body>
    <sec id="s1">
      <title>Introduction</title>
      <p>This study examines...</p>
    </sec>
  </body>
</article>
```

---

## 🔧 Integration with Wizdam Ecosystem

```
┌──────────────────────────────────────────────────┐
│                  Wizdam Editorial                 │
│  (OJS 2.x based publishing platform)             │
│                                                   │
│   ┌─────────────┐    ┌──────────────────────────┐│
│   │  Submission  │───▶│      JATS Engine         ││
│   │   (DOCX)    │    │  MetadataBuilder          ││
│   └─────────────┘    │  BodyBuilder              ││
│                       │  Parsers/Docx/*           ││
│                       └──────────┬───────────────┘│
│                                  │                │
│                                  ▼                │
│                       ┌──────────────────────────┐│
│                       │     JATS XML Output      ││
│                       │  (Ready for PubMed,      ││
│                       │   CrossRef, DOAJ)        ││
│                       └──────────────────────────┘│
└──────────────────────────────────────────────────┘
```

---

## 🤝 Contributing

Contributions are welcome! Please review our [Contributing Guidelines](https://github.com/mokesano/jats-engine/blob/main/CONTRIBUTING.md) before submitting a pull request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/new-parser`)
3. Commit your changes (`git commit -m 'Add new parser'`)
4. Push to the branch (`git push origin feature/new-parser`)
5. Open a Pull Request

This project follows the [Contributor Covenant Code of Conduct](https://github.com/mokesano/jats-engine/blob/main/CODE_OF_CONDUCT.md).

---

## 🔒 Security

**Do not publicly disclose vulnerabilities.**

- **Report to:** [security@sangia.org](mailto:security@sangia.org)
- **Response time:** Within 48 hours
- **Advisories:** [GitHub Security Advisories](https://github.com/mokesano/jats-engine/security/advisories)

Full details: [SECURITY.md](https://github.com/mokesano/jats-engine/blob/main/SECURITY.md)

---

## 📄 License

This project is licensed under the **GNU General Public License v3.0 (GPL‑3.0)**.

| Permission | Condition |
| :--- | :--- |
| ✅ Free to use (commercial & non‑commercial) | ⚠️ Derivative works must use the same license (*copyleft*) |
| ✅ Free to modify & redistribute | ⚠️ Source code must be included when distributed |

---

## 🙏 Acknowledgments

| 🏷️ Attribution | 🔗 Reference |
| :--- | :--- |
| **JATS Standard** | [ANSI/NISO Z39.96](https://www.niso.org/standards-committees/jats) — Journal Article Tag Suite |
| **OMML2MML XSLT** | Office Math to MathML transformation stylesheet |
| **Lead Developer** | [Rochmady (mokesano)](https://github.com/mokesano) |
| **Ecosystem** | [Wizdam Editorial](https://github.com/mokesano/wizdam-editorial) |
| **Sangia Publishing House** | [sangia.org](https://sangia.org/) |

---

<p align="center">
  <br>
  <sub>Built with ❤️ for the scholarly publishing community</sub>
  <br><br>
  <a href="https://github.com/mokesano/jats-engine/stargazers">
    <img src="https://img.shields.io/github/stars/mokesano/jats-engine?style=social" alt="GitHub Stars">
  </a>
  <a href="https://github.com/mokesano/jats-engine/network/members">
    <img src="https://img.shields.io/github/forks/mokesano/jats-engine?style=social" alt="GitHub Forks">
  </a>
  <br><br>
  <sub>© 2026 Rochmady. Licensed under GPL‑3.0.</sub>
</p>
