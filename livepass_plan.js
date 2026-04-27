// Plan-gating helpers — FREE 3-post cap + STANDARD+ feature gates.
//
// FREE plan: EVENT / WORKSHOP / EXHIBITION 登録は全体で3件まで。
//            PASSやチケット販売は不可。
// STANDARD+: PASS・チケット販売 + 物販ダッシュボード。上限なし。

(function (global) {
  const PLAN_KEY       = 'livepass_plan';
  const FREE_EVENT_CAP = 3;
  const CAPPED_TYPES   = new Set(['event', 'workshop', 'exhibition']);

  function currentPlan() {
    return (localStorage.getItem(PLAN_KEY) || 'free').toLowerCase();
  }

  function isFree() {
    return currentPlan() === 'free';
  }

  function isStandardOrAbove() {
    const p = currentPlan();
    return p === 'standard' || p === 'pro' || p === 'artist';
  }

  function countOwnCappedPosts() {
    const myName = (localStorage.getItem('livepass_account_name') || '').toLowerCase();
    const myUid  = localStorage.getItem('livepass_uid') || '';
    try {
      const raw  = localStorage.getItem('livepass_events');
      const list = raw ? JSON.parse(raw) : [];
      return list.filter(p => {
        if (!CAPPED_TYPES.has(p.type)) return false;
        if (myUid && p.authorUid) return p.authorUid === myUid;
        return (p.author || '').toLowerCase() === myName;
      }).length;
    } catch (_) { return 0; }
  }

  // Returns true if a FREE plan user has reached the 3-post cap.
  // Always returns false for STANDARD+ users (no cap).
  function isAtFreeEventCap() {
    if (!isFree()) return false;
    return countOwnCappedPosts() >= FREE_EVENT_CAP;
  }

  // Returns true if a FREE plan user can sell tickets / PASSes.
  // FREE = false, STANDARD+ = true.
  function canSellTickets() {
    return isStandardOrAbove();
  }

  global.LivepassPlan = {
    currentPlan,
    isFree,
    isStandardOrAbove,
    countOwnCappedPosts,
    isAtFreeEventCap,
    canSellTickets,
    FREE_EVENT_CAP,
  };
})(window);
