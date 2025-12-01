export async function createRoute(data) {
  const res = await fetch("/api/create_route", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  return res.json();
}

export async function fetchAnalytics({ groupBy, fromDate, toDate }) {
  const response = await fetch(`/api/analytics?groupBy=${groupBy}&fromDate=${fromDate}&toDate=${toDate}`); 
  if (!response.ok) {
    throw new Error('Failed to fetch analytics');
  }
  return response.json();
}
