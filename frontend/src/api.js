export async function createRoute(data) {
  const res = await fetch("/api/create_route", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  return res.json();
}

export async function fetchAnalytics(data) {
  const res = await fetch("/api/analytics", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  return res.json();
}
