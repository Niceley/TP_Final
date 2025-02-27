describe('Test E2E de la gestion des utilisateurs', () => {
    it("Ajout, modification et suppression d'un utilisateur", () => {
        cy.visit('/src/index.html');
        cy.get('#name').type('David');
        cy.get('#email').type('David.WU@example.com');
        cy.get("button[type='submit']").click();
        cy.contains('David (David.WU@example.com)').should('be.visible');
        cy.contains('David (David.WU@example.com)')
            .parent()
            .find('button')
            .contains('✏️')
            .click();
        cy.get('#name').clear().type('Juju');
        cy.get('#email').clear().type('Juju@example.com');
        cy.get("button[type='submit']").click();
        cy.contains('Juju (Juju@example.com)').should('be.visible');
        cy.contains('Juju (Juju@example.com)')
            .parent()
            .find('button')
            .contains('❌')
            .click();
        cy.contains('Juju (Juju@example.com)').should('not.exist');
    });
});