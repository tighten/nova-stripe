<template>
    <NovaStripeLayout
        heading="Stripe Dashboard"
        :meta="{ title: 'Stripe Dashboard' }"
    >
        <BalanceCard />

        <Card>
            <ChargesTable />
        </Card>
    </NovaStripeLayout>
</template>
