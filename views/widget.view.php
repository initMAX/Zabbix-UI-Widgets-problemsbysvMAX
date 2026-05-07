<?php declare(strict_types = 0);
/*
** Copyright (C) 2001-2025 Zabbix SIA
**
** This program is free software: you can redistribute it and/or modify it under the terms of
** the GNU Affero General Public License as published by the Free Software Foundation, version 3.
**
** This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
** without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
** See the GNU Affero General Public License for more details.
**
** You should have received a copy of the GNU Affero General Public License along with this program.
** If not, see <https://www.gnu.org/licenses/>.
**/


/**
 * Problems by severity widget view.
 *
 * @var CView $this
 * @var array $data
 */

use Modules\PBSM\Widget;

/**
 * Custom totals cell builder:
 * - omits severity background when count is 0
 * - applies configurable font size and padding
 */
function pbsm_getSeverityTotalsCell(int $severity, array $data, array $stat): CDiv {
	$ext_ack = array_key_exists('ext_ack', $data['filter']) ? $data['filter']['ext_ack'] : EXTACK_OPTION_ALL;
	$severity_name = CSeverityHelper::getName($severity);

	$allTriggersNum = $stat['count'];
	if ($allTriggersNum) {
		$allTriggersNum = (new CLinkAction($allTriggersNum))
			->setHint(makeProblemsPopup($stat['problems'], $data['data']['triggers'], $data['data']['actions'],
				$data['filter'], $data['allowed']
			));
	}

	$unackTriggersNum = $stat['count_unack'];
	if ($unackTriggersNum) {
		$unackTriggersNum = (new CLinkAction($unackTriggersNum))
			->setHint(makeProblemsPopup($stat['problems_unack'], $data['data']['triggers'], $data['data']['actions'],
				$data['filter'], $data['allowed']
			));
	}

	$font_size_style = 'font-size: ' . $data['counter_font_size'] . 'px; line-height: 1';

	switch ($ext_ack) {
		case EXTACK_OPTION_ALL:
			$is_zero = ($stat['count'] == 0);
			$content = [
				(new CSpan($allTriggersNum))->addClass(ZBX_STYLE_TOTALS_LIST_COUNT)->addStyle($font_size_style),
				(new CSpan($severity_name))->addClass(ZBX_STYLE_TOTALS_LIST_NAME)->setTitle($severity_name)
			];
			break;

		case EXTACK_OPTION_UNACK:
			$is_zero = ($stat['count_unack'] == 0);
			$content = [
				(new CSpan($unackTriggersNum))->addClass(ZBX_STYLE_TOTALS_LIST_COUNT)->addStyle($font_size_style),
				(new CSpan($severity_name))->addClass(ZBX_STYLE_TOTALS_LIST_NAME)->setTitle($severity_name)
			];
			break;

		case EXTACK_OPTION_BOTH:
		default:
			$is_zero = ($stat['count'] == 0);
			$content = [
				(new CSpan([$unackTriggersNum, ' '._('of').' ', $allTriggersNum]))
					->addClass(ZBX_STYLE_TOTALS_LIST_COUNT)->addStyle($font_size_style),
				(new CSpan($severity_name))->addClass(ZBX_STYLE_TOTALS_LIST_NAME)->setTitle($severity_name)
			];
			break;
	}

	$cell = new CDiv($content);

	if (!$is_zero) {
		$cell->addClass(CSeverityHelper::getStyle($severity));
	}

	$cell->addStyle('padding: ' . $data['cell_padding'] . 'px');

	return $cell;
}

/**
 * Custom totals builder using pbsm_getSeverityTotalsCell.
 */
function pbsm_makeSeverityTotals(array $data): CDiv {
	$table = new CDiv();

	foreach ($data['data']['groups'] as $group) {
		foreach ($group['stats'] as $severity => $stat) {
			if ($data['filter']['severities'] && !in_array($severity, $data['filter']['severities'])) {
				continue;
			}
			$table->addItem(pbsm_getSeverityTotalsCell($severity, $data, $stat));
		}
	}

	return $table;
}

/**
 * Custom groups table builder: applies font size, cell padding and vertical alignment.
 */
function pbsm_makeSeverityTable(array $data, $hide_empty_groups = false, ?CUrl $groupurl = null): CTableInfo {
	$table = new CTableInfo();
	$cell_height = $data['cell_padding'] * 2 + $data['counter_font_size'];
	$cell_style = 'font-size: ' . $data['counter_font_size'] . 'px;'
		. ' padding: ' . $data['cell_padding'] . 'px;'
		. ' vertical-align: middle;'
		. ' text-align: center;';

	foreach ($data['data']['groups'] as $group) {
		if ($hide_empty_groups && !$group['has_problems']) {
			continue;
		}

		if ($data['allowed']['ui_problems']) {
			$groupurl->setArgument('groupids', [$group['groupid']]);
			$row = [new CLink($group['name'], $groupurl->getUrl())];
		}
		else {
			$row = [$group['name']];
		}

		foreach ($group['stats'] as $severity => $stat) {
			if ($data['filter']['severities'] && !in_array($severity, $data['filter']['severities'])) {
				continue;
			}

			$cell = getSeverityTableCell($severity, $data, $stat);
			if ($cell !== '') {
				$cell->addStyle($cell_style);
			}
			else {
				$cell = (new CCol())->addStyle($cell_style);
			}
			$cell->setAttribute('height', $cell_height);
			$row[] = $cell;
		}

		$table->addRow(
			(new CRow($row))->setAttribute('data-hostgroupid', $group['groupid'])
		);
	}

	return $table;
}

if ($data['error'] !== null) {
	$table = new CTableInfo();
	$table->setNoDataMessage($data['error']);
}
else {
	if ($data['filter']['show_type'] == Widget::SHOW_TOTALS) {
		$table = pbsm_makeSeverityTotals($data)
			->addClass(ZBX_STYLE_BY_SEVERITY_WIDGET)
			->addClass(ZBX_STYLE_TOTALS_LIST)
			->addClass(($data['filter']['layout'] == STYLE_HORIZONTAL)
				? ZBX_STYLE_TOTALS_LIST_HORIZONTAL
				: ZBX_STYLE_TOTALS_LIST_VERTICAL
			);
	}
	else {
		$filter_severities = (array_key_exists('severities', $data['filter']) && $data['filter']['severities'])
			? $data['filter']['severities']
			: range(TRIGGER_SEVERITY_NOT_CLASSIFIED, TRIGGER_SEVERITY_COUNT - 1);

		$header = [[_x('Host group', 'compact table header'), (new CSpan())->addClass(ZBX_STYLE_ARROW_UP)]];

		for ($severity = TRIGGER_SEVERITY_COUNT - 1; $severity >= TRIGGER_SEVERITY_NOT_CLASSIFIED; $severity--) {
			if (in_array($severity, $filter_severities)) {
				$header[] = CSeverityHelper::getName($severity);
			}
		}

		$hide_empty_groups = array_key_exists('hide_empty_groups', $data['filter'])
			? $data['filter']['hide_empty_groups']
			: 0;

		$group_url = (new CUrl('zabbix.php'))
			->setArgument('action', 'problem.view')
			->setArgument('filter_set', '1')
			->setArgument('show', TRIGGERS_OPTION_RECENT_PROBLEM)
			->setArgument('hostids', array_key_exists('hostids', $data['filter']) ? $data['filter']['hostids'] : null)
			->setArgument('name', array_key_exists('problem', $data['filter']) ? $data['filter']['problem'] : null)
			->setArgument('show_suppressed',
				(array_key_exists('show_suppressed', $data['filter']) && $data['filter']['show_suppressed'] == 1) ? 1 : null
			);

		$table = pbsm_makeSeverityTable($data, $hide_empty_groups, $group_url)
			->addClass(ZBX_STYLE_BY_SEVERITY_WIDGET)
			->setHeader($header)
			->setHeadingColumn(0);
	}
}

(new CWidgetView($data))
	->addItem($table)
	->show();
