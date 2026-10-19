import test from 'node:test';
import assert from 'node:assert/strict';
import { findNextPrayer } from '../../resources/js/prayer-schedule.js';

const today = '2026-10-19';
const tomorrow = '2026-10-20';
const schedulesByDate = {
    [today]: [
        { name: 'subuh', time: '04:00' },
        { name: 'dzuhur', time: '12:04' },
        { name: 'ashar', time: '15:30' },
        { name: 'maghrib', time: '18:00' },
        { name: 'isya', time: '19:30' },
    ],
    [tomorrow]: [{ name: 'subuh', time: '04:05' }],
};

function nextAt(localDateTime) {
    return findNextPrayer(schedulesByDate, new Date(localDateTime));
}

test('selects Subuh at 03:00 before today’s first prayer', () => {
    assert.equal(nextAt('2026-10-19T03:00:00').name, 'subuh');
});

test('selects Dzuhur at 05:00 after Subuh has passed', () => {
    assert.equal(nextAt('2026-10-19T05:00:00').name, 'dzuhur');
});

test('treats a prayer at the exact current minute as already started', () => {
    assert.equal(nextAt('2026-10-19T12:04:00').name, 'ashar');
});

test('selects Ashar at 13:00 when Dzuhur has passed', () => {
    assert.equal(nextAt('2026-10-19T13:00:00').name, 'ashar');
});

test('selects Maghrib after Ashar and Isya after Maghrib', () => {
    assert.equal(nextAt('2026-10-19T17:00:00').name, 'maghrib');
    assert.equal(nextAt('2026-10-19T18:30:00').name, 'isya');
});

test('selects tomorrow’s Subuh after Isya and across midnight', () => {
    assert.equal(nextAt('2026-10-19T20:00:00').date, tomorrow);
    assert.equal(nextAt('2026-10-20T00:01:00').name, 'subuh');
    assert.equal(nextAt('2026-10-20T00:01:00').date, tomorrow);
});
